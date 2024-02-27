<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Course;
use App\Http\Requests\Api_requests\ListScheduleApiRequest;
use App\Repositories\webservices\CourseRepository;
use App\Http\Resources\ScheduleResource;

class ScheduleApiController extends AppBaseController
{
    /** @var  CourseRepository */
    private $courseRepository;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->courseRepository = $courseRepo;
    }

    public function index(ListScheduleApiRequest $request)
    {
        try {
            $input = $request->all();
            $input['order_by'] = 'time_start';
            $input['sort_by'] = 'asc';
            $msg = trans('auth.data_fetched');
            $input['master'] = 'schedule';
            $course = $this->courseRepository->getByRequest($input);
            if(count($course['result']) == 0){
                $msg = trans('auth.course_empty');
            }
            return $this->sendResponse(ScheduleResource::collection($course['result']), $msg ,$course['total_count']);
        } catch(\Exception $e) {
            \Log::info(($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }
}
