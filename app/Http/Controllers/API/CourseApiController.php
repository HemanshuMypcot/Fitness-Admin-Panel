<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Api_requests\ShowCourseApiRequest;
use App\Repositories\webservices\CourseRepository;
use App\Http\Requests\Api_requests\ListCourseApiRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ShowCourseResource;
use App\Http\Resources\GetPaymentDetailsResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;

class CourseApiController extends AppBaseController
{
    /** @var  CourseRepository */
    private $courseRepository;

    public function __construct(CourseRepository $courseRepo)
    {
        $this->courseRepository = $courseRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListCourseApiRequest $request)
    {
        try {
            $input = $request->all();
            $msg = trans('auth.data_fetched');
            $input['master'] = 'course';
            $course = $this->courseRepository->getByRequest($input);
            
            if(count($course['result']) == 0){
                $msg = trans('auth.course_empty');
            }
            return $this->sendResponse(CourseResource::collection($course['result']), $msg ,$course['total_count']);
        } catch(\Exception $e) {
            \Log::info(($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }

    public function show(ShowCourseApiRequest $request)
    {
        try {
            $input['id'] = $request->id;
            $course = $this->courseRepository->findWithoutFail($input['id']);
            if(empty($course)) {

                return $this->sendError(trans('auth.course_not_found'));
            }

            return $this->sendResponse(new ShowCourseResource($course), trans('auth.data_fetched'));
        } catch(\Exception $e) {
            \Log::info(json_encode($e->getMessage()));

            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }


    public function getPaymentDetails(ShowCourseApiRequest $request)
    {
        try {
            $input['id'] = $request->id;
            $course = $this->courseRepository->findWithoutFail($input['id']);
            if(empty($course)) {

                return $this->sendError(trans('auth.course_not_found'));
            }

            return $this->sendResponse(new GetPaymentDetailsResource($course), trans('auth.data_fetched'));
        } catch(\Exception $e) {
            \Log::info(json_encode($e->getMessage()));

            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }
}
