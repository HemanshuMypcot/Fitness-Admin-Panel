<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'instructor_id',
        'rating',
        'review',
        'updated_by',
        'created_by'
    ];
    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class,'course_id');
    }

    public function instructor()
    {
        return $this->belongsTo(\App\Models\Instructor::class,'instructor_id');
    }
}
