<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookedCourse;
use App\Models\Course;
use App\Models\User;
use App\Exports\ReportExport;
use App\Exports\BookedCourseReportExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend/report/index');
    }
    public function booked_index()
    {
        $data['courses'] = Course::all();

        return view('backend/report/booked_index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerReportExport(Request $request)
        {
        $input = $request->all();
        $dateRange = explode(' - ', $input['user_date']);
        $startDate = null;
        $endDate = null;
        $status = isset($input['status']) ? $input['status'] : null;

        if (isset($dateRange[0])) {
            $startDate = Carbon::createFromFormat('d/m/Y', trim($dateRange[0]))->format('Y-m-d');
        }

        if (isset($dateRange[1])) {
            $endDate = Carbon::createFromFormat('d/m/Y', trim($dateRange[1]))->format('Y-m-d');
        }
        $data['userData'] = [];
        $query = User::withTrashed('courses','newBookedCourse','bookedCourse');

        if (!is_null($startDate) && !is_null($endDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
           $endDate = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }
        $data['userData'] = $query->get();
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        return (new ReportExport($data))
            ->download('Customer report'. Carbon::now()->format('d-m-Y').'.xlsx');

        }

    public function bookedCourseReportExport(Request $request)
        {
        $input = $request->all();
        $dateRange = explode(' - ', $input['course_date']);
        $startDate = null;
        $endDate = null;
        $paymentStatus = isset($input['payment_status']) ? $input['payment_status'] : null;
        $status = isset($input['status']) ? $input['status'] : null;
        $paymentMode = isset($input['payment_mode']) ? $input['payment_mode'] : null;

        if (isset($dateRange[0])) {
            $startDate = Carbon::createFromFormat('d/m/Y', trim($dateRange[0]))->format('Y-m-d');
        }

        if (isset($dateRange[1])) {
            $endDate = Carbon::createFromFormat('d/m/Y', trim($dateRange[1]))->format('Y-m-d');
        }
        $data['courseData'] = [];
        $query = BookedCourse::query();

        if ($startDate && $endDate) {
           $startDate = Carbon::parse($startDate)->startOfDay();
           $endDate = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (!empty($paymentStatus)) {
            $query->where('payment_status', $paymentStatus);
        }
        if (!empty($status)) {
            if ($status == "completed") {
                $query->where('course_end_date', '<', Carbon::now())
                    ->where('status', 'booked');
            } elseif ($status == "ongoing") {
                $query->where('course_start_date', '<=', Carbon::now())
                    ->where('course_end_date', '>=', Carbon::now())
                    ->where('status', 'booked');
            } elseif ($status == "upcoming") {
                $query->where('course_start_date', '>', Carbon::now())
                    ->where('status', 'booked');
            } else {
                $query->where('status', $status);
            }
        }
        if (!empty($paymentMode)) {
            $query->where('payment_mode', $paymentMode);
        }
        if (! empty($input['course_ids'])) {
            $courseIds = $input['course_ids'];
            $query->whereIn('course_id', $courseIds);
        }

        $data['courseData'] = $query->get();
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        return (new BookedCourseReportExport($data))
            ->download('Booked Courses report'. Carbon::now()->format('d-m-Y').'.xlsx');

        }

}
