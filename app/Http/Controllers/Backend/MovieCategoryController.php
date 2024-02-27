<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MovieCategory;
use App\Http\Requests\AddMovieRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class MovieCategoryController extends Controller
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

        return view('backend/movie_category/index',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend/movie_category/add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddMovieRequest $request)
    {
        try {
            $input = $request->all();
            $genre_data=[
                "Action"=>$input['action']??'off',
                "Comedy"=>$input['comedy']??'off',
                "Thriller"=>$input['thriller']??'off',
                "Romance"=>$input['romance']??'off',
                "Emotional"=>$input['emotional']??'off'
            ];
    
            $input['genre'] = json_encode($genre_data);
            $data=MovieCategory::create($input);
            storeMedia($data, $input['image'], MovieCategory::IMAGE);

            successMessage('Data Saved Successfully', []);
        } catch (\Throwable $th) {
            errorMessage('Something went wrong!',[],'danger');
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
        $data['movie_categories'] = MovieCategory::find($id);
        $image = MovieCategory::IMAGE;
        $media = getMedia($image, $data['movie_categories']);
        if($media) {
            $data['media'] = $media;
        }
        return view('backend/movie_category/view')->with($data);
    }
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = MovieCategory::orderBy('id',"desc");
                return DataTables::of($query)
                    ->filter(function ($query) use ($request)
                    {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if (isset($request['search']['search_status']) && !is_null($request['search']['search_status'])) {
                            $query->where('status', 'like', "%" . $request['search']['search_status'] . "%");
                        }
                        // echo "<pre>";
                        // print_r($query);
                        // exit;
                        $query->get();
                    })
                    ->editColumn('category', function ($event) {
                        return $event['category']; 

                    })
                    ->editColumn('name', function ($event) {
                        return $event['name'] ?? '';
                    })
                    ->editColumn('season',function ($event) {
                        return $event['season'];
                    })->editColumn('action', function ($event) {
                        $course_category_view = checkPermission('course_category_view');
                        $course_category_add = checkPermission('course_category_add');
                        $course_category_edit = checkPermission('course_category_edit');
                        $course_category_status = checkPermission('course_category_status');
                        $course_category_delete = checkPermission('course_category_delete');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($course_category_view) {
                            $actions .= '<a href="movie_categories/view/' . $event['id'] . '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Course Category Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($course_category_edit) {
                            $actions .= ' <a href="movie_categories/edit/' . $event['id'] . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($course_category_delete) {
                            $actions .= ' <a data-option="" data-url="movie_categories/delete/' . $event->id . '" class="btn btn-danger btn-sm delete-data" title="delete"><i class="fa fa-trash"></i></a>';
                        }
                        if ($course_category_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="movie_categories/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="movie_categories/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['category', 'season','name','action'])->setRowId('id')->make(true);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['movie_category'] = MovieCategory::find($id);
        $image = MovieCategory::IMAGE;
        $movie_category = $data['movie_category'];
        $media = getMediaArray($image, $movie_category);
        if($media)
        {
            $data['media_image']=$media->getFullUrl();
            $data['media_id'] = $media->id;
            $data['media_file_name'] = $media->file_name;
        }
        return view('backend/movie_category/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $data=MovieCategory::find($_GET['id']);
            $input=$request->all();
            if(!$data){
                errorMessage('Category Not Found',[]);
            }
            $genre_data=[
                "Action"=>$input['action']??'off',
                "Comedy"=>$input['comedy']??'off',
                "Thriller"=>$input['thriller']??'off',
                "Romance"=>$input['romance']??'off',
                "Emotional"=>$input['emotional']??'off'
            ];
            $input['genre'] = json_encode($genre_data);
            $data->update($input);
            $file=MovieCategory::IMAGE;
            if(!empty($input['image'])){
                clearMediaCollection($data, $file);
                storeMedia($data, $input['image'], $file);
            }
            DB::commit();

            successMessage('Data Saved successfully', []);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateStatus(Request $request){
        try {
            DB::beginTransaction();

            $msg_data = array();
            $input = $request->all();
            // $input['updated_by'] = session('data')['id'] ?? 0;

            $homeCollection = MovieCategory::find($input['id']);
            // print_r($homeCollection);
            // exit;
            if ($homeCollection->exists()) {
                $homeCollection->update($input);
                DB::commit();
                if ($request->status == 1) {
                    successMessage(trans('message.enable'), $msg_data);
                } else {
                    errorMessage(trans('message.disable'), $msg_data);
                }
            }

            errorMessage('Movie Category not found', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: " . $e->getMessage());

            errorMessage("Something Went Wrong");
        }
    }
    public function destroy($id)
    {
        //
    }
}
