<?php

namespace App\Repositories\webservices;

//Common
use App\Models\User;
use App\Models\UserReview;
use App\Utils\SearchScopeUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

/**
 * Class UserReviewRepository
 *
 * @package App\Repositories\webservices

 * @version Dec 01, 2023
 */
class UserReviewRepository
{
    /**
     * @param $id
     * @param array $columns
     * @return mixed|null
     */
    public function findWithoutFail($id)
    {
        try {
            $user = UserReview::find($id);
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Create User Review
     *
     * @param array $attributes
     */
    public function create(array $attributes)
    {
        try {
            \DB::beginTransaction();
            $userReview = new UserReview($attributes);
            $userReview->save();
            \DB::commit();

            return $userReview;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error($e);
            throw $e;
        }
    }


    public function getByRequest(array $attributes)
    {

    }
}
