<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Models\CourseCategoryTranslation;
use App\Models\CourseCategory;
use App\Http\Requests\UpdateCourseCategoryRequest;
use App\Http\Requests\AddCourseCategoryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Utils\Utils;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['course_category_add'] = checkPermission('course_category_add');
        $data['course_category_status'] = checkPermission('course_category_status');
        $data['course_category_delete'] = checkPermission('course_category_delete');
        $data['course_category_view'] = checkPermission('course_category_view');
        $data['course_category_edit'] = checkPermission('course_category_edit');

        return view('backend/course_category/index',array('data'=>$data));
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = CourseCategory::leftJoin('course_category_translations', function ($join) {
                    $join->on("course_category_translations.course_category_id", '=', "course_categories.id");
                    $join->where('course_category_translations.locale', \App::getLocale());
                    })
                    ->select('course_categories.*')
                ->orderBy('updated_at','desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request)
                    {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('course_category_translations.category_name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if (isset($request['search']['search_status']) && !is_null($request['search']['search_status'])) {
                            $query->where('course_categories.status', 'like', "%" . $request['search']['search_status'] . "%");
                        }
                        $query->get()->toArray();
                    })->editColumn('category_name_'.\App::getLocale(), function ($event) {
                        $Key_index = array_search(\App::getLocale(), array_column($event->translations->toArray(), 'locale'));
                        return $event['translations'][$Key_index]['category_name'];

                    })
                    ->editColumn('nick_name', function ($event) {
                        return $event['nick_name'] ?? '';
                    })
                    ->editColumn('sequence',function ($event) {
                        return $event['sequence'];
                    })->editColumn('action', function ($event) {
                        $course_category_view = checkPermission('course_category_view');
                        $course_category_add = checkPermission('course_category_add');
                        $course_category_edit = checkPermission('course_category_edit');
                        $course_category_status = checkPermission('course_category_status');
                        $course_category_delete = checkPermission('course_category_delete');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($course_category_view) {
                            $actions .= '<a href="course_categories/view/' . $event['id'] . '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Course Category Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($course_category_edit) {
                            $actions .= ' <a href="course_categories/edit/' . $event['id'] . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($course_category_delete) {
                            $actions .= ' <a data-option="" data-url="course_categories/delete/' . $event->id . '" class="btn btn-danger btn-sm delete-data" title="delete"><i class="fa fa-trash"></i></a>';
                        }
                        if ($course_category_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="course_categories/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="course_categories/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['category_name_'.\App::getLocale(), 'sequence','nick_name','action'])->setRowId('id')->make(true);
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
            $instructor = CourseCategory::where('id',$input['id'])->first();

            if ($instructor->exists()) {
                $instructor->update($input);
                DB::commit();
                if ($request->status == 1) {
                    successMessage(trans('message.enable'), $msg_data);
                } else {
                    errorMessage(trans('message.disable'), $msg_data);
                }
            }
            errorMessage('course_category not found', []);
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
        $data['translated_block'] = CourseCategory::TRANSLATED_BLOCK;
        return view('backend/course_category/add',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCourseCategoryRequest $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();
            $input['created_by'] = session('data')['id'] ?? 0;
            $translated_keys = array_keys(CourseCategory::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $input[$value] = (array) json_decode($input[$value]);
            }
            $saveArray = Utils::flipTranslationArray($input, $translated_keys);
            $data = CourseCategory::create($saveArray);
            storeMedia($data, $input['image'], CourseCategory::IMAGE);
            // print_r($data);exit;
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
     * Display the specified resource.
     *
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['course_category'] = CourseCategory::find($id);
        foreach($data['course_category']['translations'] as $trans) {
            $translated_keys = array_keys(CourseCategory::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $data['course_category'][$value.'_'.$trans['locale']] = $trans[$value];
            }
        }
        $data['translated_block'] = CourseCategory::TRANSLATED_BLOCK;
        $image = CourseCategory::IMAGE;
        $course_category = $data['course_category'];
        $media = getMedia($image, $course_category);
        if($media) {
            $data['media'] = $media;
        }
        return view('backend/course_category/view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['course_category'] = CourseCategory::find($id);
        foreach($data['course_category']['translations'] as $trans) {
            $translated_keys = array_keys(CourseCategory::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $data['course_category'][$value.'_'.$trans['locale']] = str_replace("<br/>", "\r\n", $trans[$value]);
            }
        }
        $data['translated_block'] = CourseCategory::TRANSLATED_BLOCK;
        $image = CourseCategory::IMAGE;
        $course_category = $data['course_category'];
        $media = getMediaArray($image, $course_category);
        if($media) {
            $data['media_image'] = $media->getFullUrl();
            $data['media_id'] = $media->id;
            $data['media_file_name'] = $media->file_name;
        }
        return view('backend/course_category/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseCategoryRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = CourseCategory::find($_GET['id']);
            $input=$request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;
            if (!$data) {
                errorMessage('Course Category Not Found', []);
            }
            $translated_keys = array_keys(CourseCategory::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value)
            {
                $input[$value] = (array) json_decode($input[$value]);
            }
            $course_category = Utils::flipTranslationArray($input, $translated_keys);
            $data->update($course_category);
            $file = CourseCategory::IMAGE;
            if(!empty($input['image'])){
                clearMediaCollection($data, $file);
                storeMedia($data, $input['image'], $file);
            }
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseCategory $courseCategory)
    {
        // Code
    }
}
