<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UserCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['user_course_view'] = checkPermission('user_course_view');
        return view('backend/user_course/index',["data"=>$data]);
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = DB::table('course_user')->leftJoin('users', function ($join) {
                    $join->on("course_user.user_id", '=', "users.id");
                })
                    ->select('course_user.user_id', 'users.name')
                    ->distinct('course_user.user_id')
                    ->orderBy('course_user.updated_at','desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request)
                    {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('users.name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        $query->get()->toArray();
                    })->editColumn('id', function ($event) {
                        return $event->user_id;
                    })->editColumn('name', function ($event) {
                        return $event->name;
                    })->editColumn('action', function ($event) {
                        $user_course_view = checkPermission('user_course_view');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($user_course_view) {
                            $actions .= '<a href="user_course/view/' . $event->user_id. '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Category Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'action'])->make(true);
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
     * Display the specified resource.
     *
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['user'] = User::find($id);
        return view('backend/user_course/view', $data);
    }

}
