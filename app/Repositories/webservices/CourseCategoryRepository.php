<?php

namespace App\Repositories\webservices;

//Common
use App\Models\CourseCategory;
use App\Utils\SearchScopeUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Class category
 * Repository
 *
 * @package App\Repositories\webservices
 * @version Sep 25, 2023
 */
class CourseCategoryRepository
{
    /**
     * Get Book Data
     *
     * @param  int  $course_category_id
     * @return null
     */

     public function getByRequest(array $attributes)
     {
        $category = CourseCategory::where('status', '1');
        
        if (!empty($attributes['id'])) {
            $ids = explode(',', $attributes['id']);
            $category->whereIn('course_categories.id', $ids);
        }
        
        if (! empty($attributes['query']) || ! empty($attributes['order_by'])) {
            $category
            ->leftJoin('course_category_translations', function ($join) {
                $join->on("course_category_translations.course_category_id", '=', "course_categories.id");
                $join->where('course_category_translations.locale', App::getLocale());
            })
            ->select('course_categories.*');
        }
        
        if (isset($attributes['search'])) {
            $category = SearchScopeUtils::fullSearchQuery($category, $attributes['search'], 'name');
        }
        
        if (!empty($attributes['query'])) {
            $category = SearchScopeUtils::fullSearchQuery($category, $attributes['query'], 'course_category_translations.category_name');
        }
        
        $category->select('course_categories.id');
        
        $category->orderBy('sequence', 'asc');
        $data['total_count'] = $category->count();
        if (isset($attributes['paginate'])) {
            $data['result'] = $category->paginate($attributes['paginate']);
        } else {
            $data['result'] = $category->get();
        }
        return $data;
     }
}
