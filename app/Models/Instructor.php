<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends Model implements HasMedia
{

    use SoftDeletes;
    use HasFactory;
    use Translatable;
    use InteractsWithMedia;

    public $fillable = [
        'instructor_since',
        'specialist_in',
        'sequence',
        'status',
        'rating',
        'nick_name',
        'created_by',
        'updated_by'
    ];
    public $casts =[
        'instructor_since' =>'date'
    ];

    public $translatedAttributes = ['name', 'about'];

    // const IMAGE= 'instructor_image';
    public const TRANSLATED_BLOCK = [
	    'name' => 'input',
	    'about' => 'textarea'
	];


    public function instructorTranslations()
    {
        return $this->hasMany(\App\Models\InstructorTranslation::class);
    }
    public function instructorReview()
    {
        return $this->hasMany(\App\Models\UserReview::class);
    }
    public function category()
    {
        return $this->hasOne(\App\Models\CourseCategory::class, 'id', 'specialist_in');
    }

    public function specialist()
    {
        return $this->belongsTo(CourseCategory::class,'specialist_in');
    }

}
