<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Api_requests\AddBookedCourseRequest;
use App\Http\Requests\Api_requests\ListBookedCourseApiRequest;
use App\Http\Requests\Api_requests\ShowBookedCourseApiRequest;
use App\Http\Requests\Api_requests\CancelBookedCourseRequest;
use App\Repositories\webservices\BookedCourseRepository;
use App\Http\Resources\BookedCourseResource;
use App\Http\Resources\ShowBookedCourseResource;
use App\Models\Course;
use App\Models\Payment;
use App\Models\BookedCourse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Session;

class BookedCourseApiController extends AppBaseController
{
    /** @var  BookedCourseRepository */
    private $bookedCourseRepository;
    public function __construct(BookedCourseRepository $bookedCourseRepo)
    {
        $this->bookedCourseRepository = $bookedCourseRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListBookedCourseApiRequest $request)
    {
        try {
            $input = $request->all();
            $input['user_id'] = Session::get('userId');
            $msg = trans('auth.data_fetched');
            $bookedcourse = $this->bookedCourseRepository->getByRequest($input);
            if(count($bookedcourse['result']) == 0) {
                $msg = trans('auth.booked_courses_empty');
            }
            return $this->sendResponse(BookedCourseResource::collection($bookedcourse['result']), $msg, $bookedcourse['total_count']);
        } catch(\Exception $e) {
            \Log::info(($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddBookedCourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBookedCourseRequest $request)
    {
        try {
            DB::beginTransaction();
            $user_id = Session::get('userId');
            $course_id = request('course_id');

            $total_capacity = Course::find($course_id)->capacity;
            $bookings_done = BookedCourse::where('course_id', $course_id)->count();

            if($bookings_done >= $total_capacity) {
                return $this->sendError(trans('auth.bookings_full'));
            }

            $bookingExists = BookedCourse::where('user_id', $user_id)
                                         ->where('course_id', $course_id)
                                         ->first();
            if (!empty($bookingExists) && ($bookingExists->payment_mode == 'COD' || $bookingExists->payment_status == 'paid')) {
                return $this->sendError(trans('auth.course_already_booked'));
            }

            $course = Course::find($course_id);
            if (!$course) {
                return $this->sendError(trans('auth.course_not_found'));
            }
            $details = [
//                'course_category' => $course->category_name,
//                'course_name' => $course->course_name,
//                'instructor_name' => $course->instructor->name,
                'instructor_id' => $course->instructor_id,
                'course_category_id' => $course->course_category_id,
                'start_date' => $course->date_start->toDateString(),
                'end_date' => $course->date_end->toDateString(),
                'amount' => $course->amount,
                'discount' => $course->discount,
                'service_charge' => $course->service_charge,
                'service_charge_amount' => $course->service_charge_amount ?? 0,
                'tax_amount' => $course->tax_amount ?? 0,
                'discount_amount' => $course->discount_amount ?? 0,
                'tax' => $course->tax,
                'total' => $course->total,
                'course_open_at' => $course->opens_at
            ];

            $attributes = [
                'user_id' => $user_id,
                'course_id' => $course_id,
                'details' => ($details),
                'course_start_date' => $course->date_start->toDateString(),
                'course_end_date' => $course->date_end->toDateString()
            ];
            if(empty($bookingExists)) {
                $bookedCourse = $this->bookedCourseRepository->create($attributes);
            } else {
                $bookedCourse = $bookingExists;
            }

            $bookingCode = '#CB00'.Carbon::now()->format('dmY').$bookedCourse->id;
            if ($course->total == 0){
                $paymentStatus = 'free';
                $bookedCourseStatus = 'booked';
            }else{
                $paymentStatus = $request->payment_status ?? 'pending';
                $bookedCourseStatus = ($request->payment_mode == 'COD' || $request->payment_status == 'paid') ? 'booked' : ($request->payment_status == 'failed' ? 'pending' : $request->payment_status);
            }

            $updatedCourse = BookedCourse::find($bookedCourse->id)->update(
                [
                    "booking_code" => $bookingCode,
                    "payment_mode" => $request->payment_mode,
                    "status" => $bookedCourseStatus,
                    "payment_status" => $paymentStatus
                ]
            );

            $bookingExists['booking_code'] = $bookingCode;
            $bookingExists['payment_mode'] = $request->payment_mode;
            $bookingExists['payment_status'] = $request->payment_status;

            Payment::create(
                [
                    "transaction_code" => $request->transaction_code ?? null,
                    "user_id" => $user_id,
                    "booked_course_id" => $bookedCourse->id,
                    "payment_mode" => $request->payment_mode,
                    "payment_status" => $request->payment_status,
                    "amount" => $course->total,
                    "transaction_date" => Carbon::now(),
                    "remark" => $request->remark ?? null
                ]
            );
            DB::commit();
            return $this->sendResponse(new BookedCourseResource($bookedCourse), trans('auth.booked_course'));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info(json_encode($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }
    public function cancel(CancelBookedCourseRequest $request)
    {
        try {
            $user_id = Session::get('userId');
            $course_id = request('course_id');

            $bookedCourse = BookedCourse::where('user_id', $user_id)->where('course_id', $course_id)->first();
            if (empty($bookedCourse)) {
                return $this->sendError(trans('auth.course_not_booked'));
            }
            $cancel_allowed = $bookedCourse->course->cancellation_allowed;

            if($cancel_allowed == 'N'){
                return $this->sendError(trans('auth.course_cancel'));
            }

            $today = Carbon::now()->format('Y-m-d H:i:s');
            $courseStartTime = $bookedCourse->course->time_start;
            $courseDate = Carbon::parse($bookedCourse->details['start_date'].' '.$courseStartTime)->format('Y-m-d H:i:s');
            if( $courseDate <= $today) {
                return $this->sendError(trans('auth.course_already_started'));
            }

            $attributes = [
                'user_id' => $user_id,
                'course_id' => $course_id,
                'status' => 'cancelled',
            ];

            $cancelledCourse = $this->bookedCourseRepository->updateStatus($attributes);

            return $this->sendResponse(new BookedCourseResource($cancelledCourse), trans('auth.course_cancelled'));

        } catch (\Exception $e) {
            \Log::info(json_encode($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function show(ShowBookedCourseApiRequest $request)
    {
        try {
            $input['id'] = $request->id;
            $bookedcourse = $this->bookedCourseRepository->findWithoutFail($input['id']);
            if(empty($bookedcourse)) {

                return $this->sendError(trans('auth.booked_course_not_found'));
            }

            return $this->sendResponse(new ShowBookedCourseResource($bookedcourse), trans('auth.data_fetched'));
        } catch(\Exception $e) {
            \Log::info(json_encode($e->getMessage()));

            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }

}
