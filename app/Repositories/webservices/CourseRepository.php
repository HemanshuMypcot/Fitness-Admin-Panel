<?php

namespace App\Repositories\webservices;

//Common
use App\Models\Course;
use App\Models\HomeCollection;
use Carbon\Carbon;

/**
 * Class CourseRepository
 *
 * @package App\Repositories\webservices
 * @version Sep 25, 2023
 */
class CourseRepository
{
    /**
     * Get course Data
     *
     * @param  int  $course_id
     * @return null
     */
    public function findWithoutFail($course_id)
    {
        try {
            $course = Course::find($course_id);
            if (!empty($course)) {
                $course->append('instructor','category', 'latitude', 'longitude');
            }
            return $course;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getByRequest(array $attributes)
    {
        $course = Course::where('courses.status', '1')->where('visible_in_app', 'Y');

        if (!empty($attributes['master']) && $attributes['master'] == 'course'){
            $startOfDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $endOfDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
            $targetDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $course->where('registration_start', '<=', $startOfDay)
                ->where('registration_end', '>=', $endOfDay)
                ->whereRaw("(CONCAT(date_start, ' ', time_start) >= ?)", [$targetDateTime]);

        }
        if (!empty($attributes['collection_id'])) {
            $homeCollection = HomeCollection::where('type',HomeCollection::COURSE)->where('id',$attributes['collection_id'])->first();
            $timeStart = $homeCollection->time_start ?? '';
            $timeEnd = $homeCollection->time_end ?? '';
            if (!empty($timeStart) && !empty($timeEnd)){
                $course->whereBetween('time_start',[$timeStart,$timeEnd]);
            }else{
                $attributes['id'] = 0;
            }
        }

        if (!empty($attributes['id']) || isset($attributes['id'])) {
            $ids = explode(',', $attributes['id']);
            $course->whereIn('courses.id', $ids);
        }

        $courseCategory = $attributes['course_category_id'] ?? null;
        if (!empty($courseCategory)) {
            $course->where('course_category_id',$courseCategory);
        }

        $type = $attributes['type'] ?? null;
        if (!empty($type) && in_array($type, ['one_day', 'recurring'])) {
            $course->where('courses.type', $type);
        }

        $dateStart = $attributes['custom'] ?? null;
        if (!empty($dateStart)) {
            $course->whereDate('courses.date_start', $dateStart);
        }

        if(!empty($attributes['date']) && !empty($attributes['master']) && $attributes['master'] === 'schedule' ) {
            $date = $attributes['date'];
            $targetDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $course->whereDate('date_start', $date)
                ->whereRaw("(CONCAT(courses.date_start, ' ', courses.time_start) >= ?)", [$targetDateTime]);
        }

        $orderBy = $attributes['order_by'] ?? 'sequence';
        $sortBy = $attributes['sort_by'] ?? 'asc';

        $course->orderBy('courses.'.$orderBy, $sortBy);

        $data['total_count'] = $course->count();
        if (isset($attributes['paginate'])) {
            $data['result'] = $course->paginate($attributes['paginate']);
        } else {
            $data['result'] = $course->get();
        }

        return $data;
    }


}
