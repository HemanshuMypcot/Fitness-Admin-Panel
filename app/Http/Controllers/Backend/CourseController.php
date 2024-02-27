<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\AddCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Instructor;
use App\Models\Location;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['course_add'] = checkPermission('course_add');
        $data['course_status'] = checkPermission('course_status');
        $data['course_delete'] = checkPermission('course_delete');
        $data['course_view'] = checkPermission('course_view');
        $data['course_edit'] = checkPermission('course_edit');
        $data['course_categories'] = CourseCategory::all();
        $data['course_frequency'] = Course::FREQUENCY_TYPE;

        return view('backend/course/index',array('data'=>$data));
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Course::leftJoin('course_translations', function ($join) {
                    $join->on("course_translations.course_id", '=', "courses.id");
                    $join->where('course_translations.locale', \App::getLocale());
                    })
                    ->select('courses.*')
                ->orderBy('updated_at','desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request)
                    {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('course_translations.course_name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if (isset($request['search']['search_category']) && !is_null($request['search']['search_category'])) {
                            $query->where('course_category_id', $request['search']['search_category']);
                        }
                        if (isset($request['search']['search_frequency']) && !is_null($request['search']['search_frequency'])) {
                            $query->where('courses.type', 'like', "%" . $request['search']['search_frequency'] . "%");
                        }
                        if (isset($request['search']['search_start_date']) && !is_null($request['search']['search_start_date'])) {
                            $startDate = Carbon::parse($request['search']['search_start_date'])->format('Y-m-d');
                            $query->whereDate('date_start', '=', $startDate);
                        }
                        if (isset($request['search']['search_course_status']) && !is_null($request['search']['search_course_status'])) {
                            //active course
                            $condition = '>=';
                            if ($request['search']['search_course_status']=='0'){
                               //expired course
                                $condition = '<';
                            }
                            $endDate = Carbon::now()->format('Y-m-d');
                            $query->whereDate('date_end', $condition, $endDate);
                        }
                        if (!isset($request['search']['search_course_status']) && is_null($request['search']['search_course_status'])) {
                            //active course
                            $condition = '>=';
                            $endDate = Carbon::now()->format('Y-m-d');
                            $query->whereDate('date_end', $condition, $endDate);
                        }
                        if (isset($request['search']['search_status']) && !is_null($request['search']['search_status'])) {
                            $query->where('courses.status', 'like', "%" . $request['search']['search_status'] . "%");
                        }
                        $query->get()->toArray();
                    })->editColumn('course_name_'.\App::getLocale(), function ($event) {
                        $Key_index = array_search(\App::getLocale(), array_column($event->translations->toArray(), 'locale'));
                        return $event->translations[$Key_index]['course_name'].' '.'('.$event->category_name.')'."<br/>". $event->sku_code;

                    })->editColumn('type',function ($event) {
                        return ucwords(str_replace('_', ' ', $event['type'] ?? ''));
                    })->editColumn('capacity',function ($event) {
                        return $event['capacity'];
                    })->editColumn('date_start',function ($event) {
                        return $event['date_start']->format('d-m-Y');

                    })->editColumn('action', function ($event) {
                        $course_view = checkPermission('course_view');
                        $course_add = checkPermission('course_add');
                        $course_edit = checkPermission('course_edit');
                        $course_status = checkPermission('course_status');
                        $course_delete = checkPermission('course_delete');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($course_view) {
                            $actions .= '<a href="course/view/' . $event['id'] . '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Course Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($course_edit) {
                            $actions .= ' <a href="course/edit/' . $event['id'] . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($course_delete) {
                            $actions .= ' <a data-option="" data-url="course/delete/' . $event->id . '" class="btn btn-danger btn-sm delete-data" title="delete"><i class="fa fa-trash"></i></a>';
                        }
                        if ($course_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="course/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="course/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        if ($course_edit) {
                            $actions .= ' <a href="course/copy/'.$event['id'].'" class="btn btn-warning btn-sm src_data" title="Copy Course"><i class="fa fa-copy"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['sku_code','course_name_'.\App::getLocale(), 'type', 'capacity', 'date_start','action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
                \Log::error("Something Went Wrong. Error: " . $e->getMessage());
                return response([
                    'draw'            => 0,
                    'recordsTotal'    => 0,
                    'recordsFiltered' => 0,
                    'data'            => [],
                    'error'           => 'Something went wrong',
                ]);
            }
        }
    }


    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $msg_data = array();
            $input = $request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;
            $course = Course::where('id',$input['id'])->first();

            if ($course->exists()) {
                $course->update($input);
                DB::commit();
                if ($request->status == 1) {
                    successMessage(trans('message.enable'), $msg_data);
                } else {
                    errorMessage(trans('message.disable'), $msg_data);
                }
            }
            errorMessage('course not found', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: " . $e->getMessage());
            errorMessage(trans('auth.something_went_wrong'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $localeLanguage = \App::getLocale();
            $data['translated_block'] = Course::TRANSLATED_BLOCK;
            $data['course_categories'] = CourseCategory::all();
            $data['instructors'] = Instructor::all();
            $data['location'] = Location::with([
                'translations' => function ($query) use ($localeLanguage) {
                    $query->where('locale', $localeLanguage);
                },
            ])->where('status', '1')->get();

            return view('backend/course/add',$data);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    // Fetch instructors based on the provided category ID
     public function getInstructorsByCategory($id)
    {
        $instructors = Instructor::where('specialist_in', $id)->get();
        return response()->json($instructors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddCourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCourseRequest $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();
            $input['created_by'] = session('data')['id'] ?? 0;
            $input['date_start'] = Carbon::parse($input['date_start'])->format('Y-m-d');
            if (!empty($input['date_end'])){
                $input['date_end'] = Carbon::parse($input['date_end'])->format('Y-m-d');
            }
            //one_day date store
            if ($input['type']=='one_day') {
                $input['date_end'] = $input['date_start'];
                if ($input['date_start'] != $input['date_end']) {
                    errorMessage(trans('auth.start_end_same_date'));
                }
                $days = [
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                    'sunday'
                ];
                foreach($days as $day) {
                    unset($input[$day]);
                }
                $store_date = $input['date_start'];
                $day = strtolower(Carbon::parse($store_date)->format('l'));
                $input[$day] = 'on';
            }

            $working_days_data = [
                "monday"     => $input['monday'] ?? "off",
                "tuesday"    => $input['tuesday'] ?? "off",
                "wednesday"  => $input['wednesday'] ?? "off",
                "thursday"   => $input['thursday'] ?? "off",
                "friday"     => $input['friday'] ?? "off",
                "saturday"   => $input['saturday'] ?? "off",
                "sunday"   => $input['sunday'] ?? "off",
            ];

            // Tax and service charge

            $tax_total  = $input['amount']* $input['tax']/ 100;
            $service_total = $input['amount']* $input['service_charge']/ 100;
            $input['discount'] = $input['discount'] ?? 0;
            $discount_total = $input['amount']* $input['discount']/ 100;

            $input['total'] = $input['amount']+ $tax_total+ $service_total - $discount_total;

            $input['opens_at'] = $working_days_data;
            $input['registration_start'] = Carbon::parse($input['registration_start'])->startOfDay();
            $input['registration_end'] = Carbon::parse($input['registration_end'])->endOfDay();
            $translated_keys = array_keys(Course::TRANSLATED_BLOCK);

            foreach ($translated_keys as $value) {
                $input[$value] = (array) json_decode($input[$value]);
            }

            $saveArray = Utils::flipTranslationArray($input, $translated_keys);
            $data = Course::create($saveArray);
            storeMedia($data, $input['image'], Course::IMAGE);
            if(!empty($data)){
                $skuCode = $this->generateSkuCode($data);
                if(!empty($skuCode)){
                    $data->update(['sku_code'=>$skuCode]);
                }
            }
            DB::commit();

            successMessage('Data Saved successfully', []);
        } catch(\Exception $e)
        {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
        {
            $data['course'] = Course::find($id);

            // Extracting time from the start_time attribute

            $startDateTime = Carbon::parse($data['course']->time_start);
            $timeOnly = $startDateTime->format('H:i:s');
            $data['timeStart'] = $timeOnly;

            $endDateTime = Carbon::parse($data['course']->time_end);
            $timeEnd = $endDateTime->format('H:i:s');
            $data['timeEnd'] = $timeEnd;

            foreach ($data['course']['translations'] as $trans) {
                $translated_keys = array_keys(Course::TRANSLATED_BLOCK);
                foreach ($translated_keys as $value) {
                    $data['course'][$value . '_' . $trans['locale']] = $trans[$value];
                }
            }

            $data['translated_block'] = Course::TRANSLATED_BLOCK;
            $course = $data['course'];
            $image = Course::IMAGE;
            $media = getMedia($image, $course);

            if ($media) {
                $data['media'] = $media;
            }

            return view('backend/course/view', $data);

        }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['course'] = Course::find($id);
        foreach($data['course']['translations'] as $trans) {
            $translated_keys = array_keys(Course::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $data['course'][$value.'_'.$trans['locale']] = str_replace("<br/>", "\r\n", $trans[$value]);
            }
        }
        $localeLanguage = \App::getLocale();
        $data['location'] = Location::with([
            'translations' => function ($query) use ($localeLanguage) {
                $query->where('locale', $localeLanguage);
            },
        ])->where('status', '1')->get();

        $data['course_categories'] = CourseCategory::all();
        $data['instructors'] = Instructor::where('specialist_in', $data['course']->course_category_id )->get();
        $data['translated_block'] = Course::TRANSLATED_BLOCK;

        $image = Course::IMAGE;
        $course = $data['course'];
        $media = getMediaArray($image, $course);
        if($media) {
            $data['media_image'] = $media->getFullUrl();
            $data['media_id'] = $media->id;
            $data['media_file_name'] = $media->file_name;
        }

        return view('backend/course/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCourseRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseRequest $request)
    {
        try {

            DB::beginTransaction();
            $data = Course::find($request['course_id']);
            $input=$request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;
            $input['date_start'] = Carbon::parse($input['date_start'])->format('Y-m-d');
            if (!empty($input['date_end'])){
                $input['date_end'] = Carbon::parse($input['date_end'])->format('Y-m-d');
            }
            if ($input['type'] == 'one_day'){
                $input['date_end'] = $input['date_start'];
            }
            //one_day date store
            if ($input['type']=='one_day' && $input['date_start'] != $input['date_end']) {
                errorMessage(trans('auth.start_end_same_date'));
            }

            $working_days_data = [
                "monday"     => $input['monday'] ?? "off",
                "tuesday"    => $input['tuesday'] ?? "off",
                "wednesday"  => $input['wednesday'] ?? "off",
                "thursday"   => $input['thursday'] ?? "off",
                "friday"     => $input['friday'] ?? "off",
                "saturday"   => $input['saturday'] ?? "off",
                "sunday"   => $input['sunday'] ?? "off",
            ];

            $tax_total  = $input['amount']* $input['tax']/ 100;
            $service_total = $input['amount']* $input['service_charge']/ 100;
            $input['discount'] = $input['discount'] ?? 0;
            $discount_total = $input['amount']* $input['discount']/ 100;
            $input['total'] = $input['amount']+ $tax_total+ $service_total - $discount_total;

            $input['opens_at'] = $working_days_data;
            $input['registration_start'] = Carbon::parse($input['registration_start'])->startOfDay();
            $input['registration_end'] = Carbon::parse($input['registration_end'])->endOfDay();
            if (!$data) {
                errorMessage('Course Not Found', []);
            }

            $translated_keys = array_keys(Course::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value)
            {
                $input[$value] = (array) json_decode($input[$value]);
            }

            $course = Utils::flipTranslationArray($input, $translated_keys);
            $data->update($course);
            $file = Course::IMAGE;
            if(!empty($input['image'])){
                clearMediaCollection($data, $file);
                storeMedia($data, $input['image'], $file);
            }
            if(!empty($data)){
                $skuCode = $this->generateSkuCode($data);
                if(!empty($skuCode)){
                    $data->update(['sku_code'=>$skuCode]);
                }
            }

            DB::commit();
            successMessage('Data Saved successfully', []);
        } catch (\Exception $e) {
            DB::rollBack();
            errorMessage('Error occurred: ' . $e->getMessage(), []);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function copyCourse($id)
    {
        $data['course'] = Course::find($id);
        foreach ($data['course']['translations'] as $trans) {
            $translated_keys = array_keys(Course::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $data['course'][$value.'_'.$trans['locale']] = str_replace("<br/>", "\r\n", $trans[$value]);
            }
        }
        $localeLanguage = \App::getLocale();
        $data['location'] = Location::with([
            'translations' => function ($query) use ($localeLanguage) {
                $query->where('locale', $localeLanguage);
            },
        ])->where('status', '1')->get();

        $data['course_categories'] = CourseCategory::all();
        $data['instructors'] = Instructor::where('specialist_in', $data['course']->course_category_id )->get();
        $data['translated_block'] = Course::TRANSLATED_BLOCK;


        return view('backend/course/copy', $data);
    }

    public function generateSkuCode($course)
    {
        $categoryName = $course->category->nick_name ?? '';
        $instructorName = $course->instructor->nick_name ?? '';
        $courseType = $course->type ?? '';
        $courseStartDate = Carbon::parse($course->date_start)->format('dmy') ?? '';
        $courseStartTime = date('Hi', strtotime($course->time_start)) ?? '';

        return $categoryName.'-'.$instructorName.'-'.strtoupper($courseType[0]).'-'.$courseStartDate.'-'.$courseStartTime;
    }
}
