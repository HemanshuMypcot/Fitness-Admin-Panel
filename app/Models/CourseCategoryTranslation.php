<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategoryTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'course_category_id',
        'category_name'

    ];
    public function category()
    {
        return $this->belongsTo(\App\Models\CourseCategory::class);
    }
    public function setCategoryNameAttribute($value)
    {
        $this->attributes['category_name'] = strip_tags($value);
    }
}
