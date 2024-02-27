<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBookingCourseRequest;
use App\Models\BookedCourse;
use App\Models\User;
use App\Models\Course;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class BookedCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['booked_course_view'] = checkPermission('booked_course_view');
        $data['course_data'] = Course::all();
        return view('backend/booked_course/index',["data"=>$data]);
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = BookedCourse::with('user');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request)
                    {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                           $user_ids = User::where('name', 'like', "%" . $request['search']['search_name'] . "%")->pluck('id')->toArray();
                           $query->whereIn('user_id', $user_ids);
                        }
                        if (isset($request['search']['search_booking_code']) && !is_null($request['search']['search_booking_code'])) {
                            $query->where('booked_courses.booking_code', 'like', "%" . $request['search']['search_booking_code'] . "%");
                        }
                        if (isset($request['search']['search_course_name']) && !is_null($request['search']['search_course_name'])) {
                            $query->where('course_id', $request['search']['search_course_name']);
                        }
                        if (isset($request['search']['search_booking_status']) && !is_null($request['search']['search_booking_status'])) {
                            if($request['search']['search_booking_status'] == "completed"){
                                $query->where('booked_courses.course_end_date', '<', Carbon::now())
                                ->where('booked_courses.status','booked');
                            }elseif ($request['search']['search_booking_status'] == "ongoing"){
                                $query->where('booked_courses.course_start_date', '<=', Carbon::now())
                                ->where('booked_courses.course_end_date', '>=', Carbon::now())
                                ->where('booked_courses.status','booked');
                            }elseif ($request['search']['search_booking_status'] == "upcoming"){
                                $query->where('booked_courses.course_start_date', '>', Carbon::now())
                                ->where('booked_courses.status','booked');
                            }else{
                                $query->where('booked_courses.status', $request['search']['search_booking_status']);
                            }
                        }
                        if (isset($request['search']['search_payment_status']) && !is_null($request['search']['search_payment_status'])) {
                            $query->where('booked_courses.payment_status', $request['search']['search_payment_status']);
                        }
                        if (isset($request['search']['search_booking_date']) && !is_null($request['search']['search_booking_date'])) {
                            $bookingDate = $request['search']['search_booking_date'];
                            $query->whereDate('booked_courses.created_at', '=', $bookingDate);
                        }
                        $query->get()->toArray();
                    })->editColumn('id', function ($event) {
                        return $event->id;
                    })->editColumn('booking_code', function ($event) {
                        return $event->booking_code;
                    })->editColumn('name', function ($event) {
                        return ucfirst($event->user ? $event->user->name : '');
                    })->editColumn('course_name', function ($event) {
                        return ($event->details['course_name']);
                    })->editColumn('instructor_name', function ($event) {
                        return ($event->details['instructor_name']);
                    })->editColumn('status', function ($event) {
                        return ($event->status);
                    })->editColumn('payment_mode', function ($event) {
                        return ($event->payment_mode);
                    })->editColumn('payment_status', function ($event) {
                        return ($event->payment_status);
                    })->editColumn('action', function ($event) {
                        $booked_course_view = checkPermission('booked_course_view');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($booked_course_view) {
                            $actions .= '<a href="booked_course/view/' . $event->id. '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Category Details" title="View"><i class="fa fa-eye"></i></a>';
                            if(strtolower($event->payment_status) == 'pending'){
                                $actions .= ' <a  href="booked_course/mark_as_paid/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Mark as paid"><i class="fa fa-money" aria-hidden="true"></i></a>';


                            }                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['booking_code', 'name', 'course_name', 'instructor_name','status', 'payment_mode', 'payment_status', 'action'])->make(true);
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

    public function show($id)
    {
        // Fetch the booked course details with relationships
        $data['course'] = BookedCourse::find($id);

        return view('backend/booked_course/view',['data' =>$data]);
    }
    public function markAsPaid($id)
    {
        $data['bookedCourse'] = BookedCourse::find($id);

        return view('backend/booked_course/mark_as_paid')->with($data);
    }

    public function updateBookingSatatus(UpdateBookingCourseRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $bookedCourse = BookedCourse::find($input['booking_id']);
            $input = [
                'payment_status' => 'paid',
                'status' => 'booked',
                'updated_by' =>  session('data')['id'] ?? 0
            ];
            if (!empty($bookedCourse)){
                $bookedCourse->update($input);
            }
            DB::commit();

            successMessage('Updated Successfully',[]);
        }catch(\Exception $e) {

            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }
}
