<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\BookedCourse;
use App\Models\Course;
use App\Models\CourseCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use App\Models\Instructor;
use App\Models\InstructorTranslation;
use Illuminate\Support\Facades\DB;
use App\Utils\Utils;
use Illuminate\Http\Request;
use App\Http\Requests\AddInstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['instructor_add'] = checkPermission('instructor_add');
        $data['instructor_status'] = checkPermission('instructor_status');
        $data['instructor_delete'] = checkPermission('instructor_delete');
        $data['instructor_view'] = checkPermission('instructor_view');
        $data['instructor_edit'] = checkPermission('instructor_edit');
        $data['course_categories'] = CourseCategory::all();

        return view('backend/instructor/index',array('data'=>$data));
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Instructor::with('specialist')->leftJoin('instructor_translations', function ($join) {
                    $join->on("instructor_translations.instructor_id", '=', "instructors.id");
                    $join->where('instructor_translations.locale', \App::getLocale());
                    })
                    ->select('instructors.*')
                ->orderBy('updated_at','desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request)
                    {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('instructor_translations.name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if (isset($request['search']['search_designation']) && !is_null($request['search']['search_designation'])) {
                            $specialistId = $request['search']['search_designation'];
                            $query->whereHas('specialist', function ($translationQuery) use ($specialistId) {
                                $translationQuery->where('course_categories.id',$specialistId);
                            });
                        }
                        if (isset($request['search']['search_status']) && !is_null($request['search']['search_status'])) {
                            $query->where('instructors.status', 'like', "%" . $request['search']['search_status'] . "%");
                        }
                        $query->get()->toArray();
                    })->editColumn('name_'.\App::getLocale(), function ($event) {
                        $Key_index = array_search(\App::getLocale(), array_column($event->translations->toArray(), 'locale'));
                        return $event['translations'][$Key_index]['name'];
                    })->editColumn('sequence',function ($event) {
                        return $event['sequence'];
                    })->editColumn('rating',function ($event) {
                        return $event['rating'];
                    })->editColumn('specialist_in', function ($event) {
                        return $event['specialist']['category_name'] ?? '';
                    })
                    ->editColumn('nick_name', function ($event) {
                        return $event['nick_name'] ?? '';
                    })
                    ->editColumn('action', function ($event) {
                        $instructor_view = checkPermission('instructor_view');
                        $instructor_add = checkPermission('instructor_add');
                        $instructor_edit = checkPermission('instructor_edit');
                        $instructor_status = checkPermission('instructor_status');
                        $instructor_delete = checkPermission('instructor_delete');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($instructor_view) {
                            $actions .= '<a href="instructors/view/' . $event['id'] . '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Book Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($instructor_edit) {
                            $actions .= ' <a href="instructors/edit/' . $event['id'] . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($instructor_delete) {
                            $actions .= ' <a data-option="" data-url="instructors/delete/' . $event->id . '" class="btn btn-danger btn-sm delete-data" title="delete"><i class="fa fa-trash"></i></a>';
                        }
                        if ($instructor_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="instructors/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="instructors/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name_'.\App::getLocale(),'sequence','rating', 'specialist_in','nick_name','action'])->setRowId('id')->make(true);
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AddInstructorRequest $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();
            $input['created_by'] = session('data')['id'] ?? 0;
            $input['instructor_since'] =Carbon::parse($input['instructor_since'])->format('Y-m-d');
            $translated_keys = array_keys(Instructor::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $input[$value] = (array) json_decode($input[$value]);
            }
            $saveArray = Utils::flipTranslationArray($input, $translated_keys);
            $data = Instructor::create($saveArray);
            // storeMedia($data, $input['image'], Instructor::IMAGE);
            DB::commit();

            successMessage('Data Saved successfully', []);
        }catch(\Exception $e)
        {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['translated_block'] = Instructor::TRANSLATED_BLOCK;
        $data['course_categories'] = CourseCategory::all();

        return view('backend/instructor/add',$data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['instructor'] = Instructor::find($id);
        foreach($data['instructor']['translations'] as $trans) {
            $translated_keys = array_keys(Instructor::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $data['instructor'][$value.'_'.$trans['locale']] = $trans[$value];
            }
        }
        $data['translated_block'] = Instructor::TRANSLATED_BLOCK;
        // $image = Instructor::IMAGE;
        // $instructor = $data['instructor'];
        // $media = getMedia($image, $instructor);
        // if($media) {
        //     $data['media'] = $media;
        // }
        return view('backend/instructor/view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['instructor'] = Instructor::find($id);
        foreach($data['instructor']['translations'] as $trans) {
            $translated_keys = array_keys(Instructor::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $data['instructor'][$value.'_'.$trans['locale']] = str_replace("<br/>", "\r\n", $trans[$value]);
            }
        }
        $data['translated_block'] = Instructor::TRANSLATED_BLOCK;
        $data['course_categories'] = CourseCategory::all();
        // $image = Instructor::IMAGE;
        // $instructor = $data['instructor'];
        // $media = getMediaArray($image, $instructor);
        // if($media) {
        //     $data['media_image'] = $media->getFullUrl();
        //     $data['media_id'] = $media->id;
        //     $data['media_file_name'] = $media->file_name;
        // }
        return view('backend/instructor/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInstructorRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = Instructor::find($_GET['id']);
            $input=$request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;
            if (!$data) {
                errorMessage('instructor Not Found', []);
            }
            $input['instructor_since'] =Carbon::parse($input['instructor_since'])->format('Y-m-d');
            $translated_keys = array_keys(Instructor::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value)
            {
                $input[$value] = (array) json_decode($input[$value]);
            }
            $instructor = Utils::flipTranslationArray($input, $translated_keys);
            $data->update($instructor);
            // $file = Instructor::IMAGE;
            // if(!empty($input['image'])){
            //     clearMediaCollection($data, $file);
            //     storeMedia($data, $input['image'], $file);
            // }
            DB::commit();

            successMessage('Data Saved successfully', []);
        }catch(\Exception $e)
        {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $msg_data = array();
            $input = $request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;
            $instructor = Instructor::where('id',$input['id'])->first();

            $coursesCount = Course::where('instructor_id', $input['id'])
                            ->where('date_start', '>',Carbon::now()->endOfDay())
                            ->count();
            $flag = false;
            if ($coursesCount > 0) {
                $flag = true;
            }
            if ($request->status == 0 && $flag == true) {
                errorMessage('', $msg_data,'' ,2);
            }
            if ($instructor->exists()) {
                $instructor->update($input);
                DB::commit();

                if ($request->status == 1) {
                    successMessage(trans('message.enable'), $msg_data);
                } else {
                    errorMessage(trans('message.disable'), $msg_data);
                }
            }
            errorMessage('instructor not found', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: " . $e->getMessage());
            errorMessage(trans('auth.something_went_wrong'));
        }
    }
    public function forceUpdateStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            $msg_data = array();
            $input = $request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;
            $instructor = Instructor::where('id',$input['id'])->first();
            if ($instructor->exists()) {
                $instructor->update($input);
                DB::commit();

                if ($request->status == 1) {
                    successMessage(trans('message.enable'), $msg_data);
                } else {
                    errorMessage(trans('message.disable'), $msg_data);
                }
            }
        }catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: " . $e->getMessage());
            errorMessage(trans('auth.something_went_wrong'));
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
}
