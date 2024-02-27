<?php

namespace App\Repositories\webservices;

//Common
use App\Models\BookedCourse;
use App\Utils\SearchScopeUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BookedCourseRepository
 *
 * @package App\Repositories\webservices

 * @version Sep 23, 2023
 */
class BookedCourseRepository
{
    /**
     * @param $id
     * @param array $columns
     * @return mixed|null
     */
    public function findWithoutFail($id)
    {
        try {
            $bookedCourse = BookedCourse::find($id);
            if (!empty($bookedCourse)) {
                $bookedCourse->append('latitude', 'longitude');
            }
            return $bookedCourse;
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        try {
            DB::beginTransaction();
            $bookedCourse = BookedCourse::create($attributes);
            DB::commit();

            return $bookedCourse;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
    }

    public function getByRequest(array $attributes)
    {
        $bookedCourse = BookedCourse::where('user_id', $attributes['user_id']);

        if (!empty($attributes['id'])) {
            $ids = explode(',', $attributes['id']);
            $bookedCourse->whereIn('booked_courses.id', $ids);
        }

        $orderBy = $attributes['order_by'] ?? 'created_at';
        $sortBy = $attributes['sort_by'] ?? 'desc';

        $bookedCourse->orderBy('booked_courses.'.$orderBy, $sortBy);

        $data['total_count'] = $bookedCourse->count();
        if (isset($attributes['paginate'])) {
            $data['result'] = $bookedCourse->paginate($attributes['paginate']);
        } else {
            $data['result'] = $bookedCourse->get();
        }

        return $data;
    }

    public function updateStatus(array $attributes)
    {
        try {
            \DB::beginTransaction();

            $cancelledCourse = BookedCourse::where('user_id', $attributes['user_id'])
                ->where('course_id', $attributes['course_id'])
                ->first();

            if ($cancelledCourse) {
                $cancelledCourse->update(['status' => $attributes['status']]);
            }

            \DB::commit();

            return $cancelledCourse;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error($e);
            throw $e;
        }
    }
}
