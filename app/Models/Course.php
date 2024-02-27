<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Astrotomic\Translatable\Translatable;
use App\Utils\ApiUtils;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Course extends Model implements HasMedia
{
    use SoftDeletes;
    use HasFactory;
    use Translatable;
    use InteractsWithMedia;

    protected $fillable = [
        'sku_code',
        'course_category_id',
        'instructor_id',
        'location_id',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
        'capacity',
        'sequence',
        'amount',
        'tax',
        'total',
        'service_charge',
        'discount',
        'opens_at',
        'registration_start',
        'registration_end',
        'type',
        'status',
        'registration_allowed',
        'cancellation_allowed',
        'visible_in_app',
        'created_by',
        'updated_by',
    ];


    public $casts =[
        'course_category_id' =>'integer',
        'instructor_id' =>'integer',
        'location_id' => 'integer',
        'date_start' =>'date',
        'date_end' =>'date',
        'time_start' =>'string',
        'time_end' =>'datetime',
        'capacity' =>'integer',
        'sequence' =>'integer',
        'amount' => 'string',
        'tax' => 'string',
        'total' =>'string',
        'service_charge' =>'string',
        'opens_at' => 'json',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'type' => 'string',
        'status' => 'boolean',
        'registration_allowed' => 'string',
        'cancellation_allowed' => 'string',
        'created_by' => 'integer',
        'updated_by'=> 'integer'
    ];

    public const FREQUENCY_TYPE = [
        'one_day',
        'recurring'
    ];

    public $translatedAttributes = ['course_name', 'course_details','address', 'additional_requirement', 'duration_time'];

    const IMAGE= 'course_image';

    public const TRANSLATED_BLOCK = [
	    'course_name' => 'input',
        'course_details' => 'textarea',
        'address' => 'textarea',
        'additional_requirement' => 'textarea'
	];

    public function courseTranslations()
    {
        return $this->hasMany(\App\Models\CourseTranslation::class);
    }

    public function getCategoryNameAttribute()
    {
        return $this->category->category_name;
    }

    public function getInstructorNameAttribute()
    {
        return $this->instructor;
    }

    public function getLatitudeAttribute()
    {
        return $this->location->latitude ?? '';
    }
    public function getLongitudeAttribute()
    {
        return $this->location->longitude ?? '';
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\CourseCategory::class, 'course_category_id');
    }

    public function instructor()
    {
        return $this->belongsTo(\App\Models\Instructor::class, 'instructor_id');
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id');
    }

    public function courseRating()
    {
        return $this->hasMany(\App\Models\UserReview::class);
    }
    public function getDurationTimeAttribute()
    {
        $apiUtils = new ApiUtils();
        $duration = $this->time_end->diffInMinutes($this->time_start);

        return $apiUtils->covertDurationTime($duration);
    }

    public function getImageUrlAttribute(): string
    {

        /** @var Media $media */
        $media = $this->getMedia(self::IMAGE)->first();

        return ! empty($media) ? $media->getFullUrl() : '';
    }
    public function getCourseThumbImageUrlAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::IMAGE)->first();
        
        if (!empty($media) && !empty($media['custom_properties'])){
            return $media->getUrl('thumbnails');
        }
        
        return  $this->image_url ?? '';
    }

    public function getDiscountAmountAttribute()
    {
        $subTotalAmount = $this->amount ?? 0;
        $discount = $this->discount ?? 0;

        return ($subTotalAmount * $discount / 100);
    }
    public function getServiceChargeAmountAttribute()
    {
        $subTotalAmount = $this->amount ?? 0;
        $serviceCharge = $this->service_charge ?? 0;

        return ($subTotalAmount * $serviceCharge / 100);
    }
    public function getTaxAmountAttribute()
    {
        $subTotalAmount = $this->amount ?? 0;
        $tax = $this->tax ?? 0;

        return ($subTotalAmount * $tax / 100);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnails')
            ->width(175)
            ->height(100)
            ->nonQueued();
        
    }
}
