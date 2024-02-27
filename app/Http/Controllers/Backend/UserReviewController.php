<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\UserReview;
use App\Models\User;
use App\Models\Course;

class UserReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['user_review'] = checkPermission('user_review');
        $data['user_review_view'] = checkPermission('user_review_view');
        $data['course_data'] = Course::all();
        $data['instructor_data'] = Instructor::all();

        return view('backend/user_review/index', array('data' => $data));
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = UserReview::with('course.translations', 'instructor.translations')
                    ->orderBy('user_reviews.updated_at', 'desc');

                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {

                        if (isset($request['search']['rating']) && !is_null($request['search']['rating'])) {
                            $query->where('rating', $request['search']['rating']);
                        }
                        if (isset($request['search']['course_name']) && !is_null($request['search']['course_name'])) {
                            $query->where('course_id', $request['search']['course_name']);
                        }
                        if (isset($request['search']['instructor_name']) && !is_null($request['search']['instructor_name'])) {
                            $query->where('instructor_id', $request['search']['instructor_name']);
                        }

                        $query->get()->toArray();
                    })
                    ->editColumn('user_name', function ($review) {
                        $user = User::withTrashed()->find($review->created_by);
                        return $user ? $user->name : '';
                    })
                    ->editColumn('course_name', function ($review) {
                        $courseTranslations = $review->course->translations->where('locale', \App::getLocale())->first();
                        return $courseTranslations ? $courseTranslations->course_name : '';
                    })
                    ->editColumn('instructor_name', function ($review) {
                        $instructorTranslations = $review->instructor->translations->where('locale', \App::getLocale())->first();
                        return $instructorTranslations ? $instructorTranslations->name : '';
                    })
                    ->editColumn('rating', function ($review) {
                        return $review->rating;
                    })
                    ->addColumn('action', function ($review) {
                        return '<a href="user_review/view/' . $review->id . '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Review" title="View"><i class="fa fa-eye"></i></a>';
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->setRowId('id')
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error("Something Went Wrong. Error: " . $e->getMessage());
                return response([
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Something went wrong',
                ]);
            }
        }

    }

    public function show($id)
    {
        $data['user_review'] = UserReview::find($id);
        return view('backend/user_review/view',$data);
    }

}
