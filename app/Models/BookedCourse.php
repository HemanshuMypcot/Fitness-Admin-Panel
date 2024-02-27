<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookedCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'details',
        'course_start_date',
        'course_end_date',
        'status',
        'booking_code',
        'payment_status',
        'payment_mode',
        'updated_by'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'course_start_date',
        'course_end_date',
    ];

    public $casts = [
        'details' => 'json'
    ];

    public function getLatitudeAttribute()
    {
        return $this->course->location->latitude ?? '';
    }
    public function getLongitudeAttribute()
    {
        return $this->course->location->longitude ?? '';
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class,'course_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id')->withTrashed();
    }

    public function courseTranslations()
    {
        return $this->hasMany(CourseTranslation::class, 'course_id', 'course_id');
    }

    public function getdetailsAttribute($value)
    {
        $details = json_decode($value, true);
        $course = Course::find($this->course_id ?? '');
        $details['course_name'] = $course->course_name ?? '';
        $courseCategory = CourseCategory::find($details['course_category_id'] ?? '');
        $details['course_category'] = $courseCategory->category_name ?? '';
        $instructor = Instructor::find($details['instructor_id'] ?? '');
        $details['instructor_name'] = $instructor->name ?? '';

        return $details;
    }

    public function getStatusAttribute($value)
    {
        if ($value == 'booked' && ! empty($this->details['start_date']) && ! empty($this->details['end_date'])) {
            $courseStartTime = Carbon::parse($this->course->time_start)->format('H:i:s');
            $courseEndTime = Carbon::parse($this->course->time_end)->format('H:i:s');
            $courseStartDate = Carbon::parse($this->details['start_date'].' '.$courseStartTime)->format('Y-m-d H:i:s');
            $courseEndDate = Carbon::parse($this->details['end_date']. ' '.$courseEndTime)->format('Y-m-d H:i:s');
            $now = Carbon::now();
            if ($courseStartDate > $now) {
                $value = 'upcoming';
            } elseif ($courseStartDate <= $now && $courseEndDate >= $now) {
                $value = 'ongoing';
            } elseif ($courseEndDate < $now) {
                $value = 'completed';
            }
        }

        return ucfirst($value);
    }

}
