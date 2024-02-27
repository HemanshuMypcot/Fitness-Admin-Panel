<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\webservices\UserReviewRepository;
use App\Http\Requests\Api_requests\AddUserReviewRequest;
use App\Models\UserReview;
use App\Models\Instructor;
use Session;

class InstructorApiController extends AppBaseController
{

    /** @var  UserReviewRepository */
    private $userReviewRepository;
    public function __construct(UserReviewRepository $userReviewRepo)
    {
        $this->userReviewRepository = $userReviewRepo;
    }
    public function addReview(AddUserReviewRequest $request)
    {
        try {
            $attributes = $request->all();
            $attributes['created_by'] = Session::get('userId');

            $existingReview = UserReview::where('course_id', $attributes['course_id'])
                ->where('instructor_id', $attributes['instructor_id'])
                ->where('created_by', $attributes['created_by'])
                ->first();

            if ($existingReview) {
                return $this->sendError(trans('auth.user_review_exists'));
            }

            $this->userReviewRepository->create($attributes);

            $instructorReviews = UserReview::where('instructor_id', $attributes['instructor_id'])->get();
            $average_rating = "";
            if (!empty($instructorReviews->toArray())) {
                $ratings = array_column($instructorReviews->toArray(), "rating");
                $average_rating = array_sum($ratings)/count($ratings);
                Instructor::find($attributes['instructor_id'])->update(
                    [
                        "rating" => $average_rating
                    ]
                );
            }
            return $this->sendResponse(array(), trans('auth.review_created'));

        } catch (\Exception $e) {
            \Log::info(json_encode($e->getMessage()));

            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }
}
