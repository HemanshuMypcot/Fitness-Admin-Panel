<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'locale',
        'course_name',
        'course_details',
        'address',
        'additional_requirement'
    ];

    public $casts = [
        'id'                  => 'integer',
        'course_id'           => 'integer',
        'course_name'         => 'string',
        'course_details'      => 'string',
    ];

    public function courses()
    {
        return $this->belongsTo(\App\Models\Course::class);
    }
    public function setCourseNameAttribute($value)
    {
        $this->attributes['course_name'] = strip_tags($value);
    }
    public function setCourseDetailsAttribute($value)
    {
        $this->attributes['course_details'] = str_replace("||br||", "<br/>", strip_tags(str_replace("<br/>", "||br||", $value)));

    }
    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = str_replace("||br||", "<br/>", strip_tags(str_replace("<br/>", "||br||", $value)));

    }
    public function setAdditionalRequirementAttribute($value)
    {
        $this->attributes['additional_requirement'] = str_replace("||br||", "<br/>", strip_tags(str_replace("<br/>", "||br||", $value)));

    }
}
