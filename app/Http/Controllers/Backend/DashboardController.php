<?php
/*
    *   Developed by : Ankita - Mypcot Infotech
    *   Created On : 03-07-2023
    *   https://www.mypcot.com/
*/

namespace App\Http\Controllers\Backend;

use App\Models\BookedCourse;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Instructor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['users_total'] = User::where('status', 1)->count();
        $data['instructor_total'] = Instructor::where('status', 1)->count();
        $data['course_total'] = Course::where('status', 1)->count();
        $data['booked_course_total'] = BookedCourse::select('course_id')
                                                    ->distinct()
                                                    ->get()
                                                    ->count();
        if(session('data')['role_id'] != 1){
            return view('backend/dashboard/staff_dashboard', $data);
        }
        return view('backend/dashboard/index', $data);
    }

    public function userDashboardChart()
    {
		$months = [];
        $currentMonth = Carbon::now();
        for ($i = 0; $i < 6; $i++) {
            $months[] = $currentMonth->copy()->startOfMonth()->subMonths($i)->format('Y-m');
        }
        $user_data = \DB::table('users')
            ->select(\DB::raw('DATE_FORMAT(created_at, "%Y-%m") AS month'), \DB::raw('COUNT(*) AS count'))
            ->where('status', '=', 1)
            ->whereIn(\DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $months)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        // Convert the result to an associative array
        $user_data = $user_data->pluck('count', 'month')->all();
        $result = [];
        foreach ($months as $month) {
            $result[] = [
                'month' => date('M-y', strtotime($month)),
                'count' => isset($user_data[$month]) ? $user_data[$month] : 0,
            ];
        }
        return array_values(array_reverse($result));
	}
    public function bookedCourseDashboardChart()
    {
		$months = [];
        $currentMonth = Carbon::now();
        for ($i = 0; $i < 6; $i++) {
            $months[] = $currentMonth->copy()->startOfMonth()->subMonths($i)->format('Y-m');
        }
        $course_data = \DB::table('booked_courses')
            ->select(\DB::raw('DATE_FORMAT(created_at, "%Y-%m") AS month'), \DB::raw('COUNT(*) AS count'))
            ->where('status', 'booked')
            ->whereIn(\DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $months)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        // Convert the result to an associative array
        $course_data = $course_data->pluck('count', 'month')->all();
        $result = [];
        foreach ($months as $month) {
            $result[] = [
                'month' => date('M-y', strtotime($month)),
                'count' => isset($course_data[$month]) ? $course_data[$month] : 0,
            ];
        }
        return array_values(array_reverse($result));
	}
}
