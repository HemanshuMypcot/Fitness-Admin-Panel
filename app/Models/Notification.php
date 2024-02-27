<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Notification extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $fillable = [
        'title',
        'body',
        'type',
        'selected_id',
        'image',
        'last_sent',
        'batch_count',
        'status',
        'created_by',
        'updated_by'
    ];

    public $casts = [
        'title' => 'string',
        'body' => 'string',
        'type' => 'string',
        'selected_id' => 'integer',
        'image' => 'string',
        'last_sent' => 'datetime',
    ];

    public const CATEGORY = 'category';
    public const COURSE = 'course';
    public const COLLECTION = 'collection';
    public const NOTIFICATION_TYPE = [
        self::CATEGORY              => 'category',
        self::COURSE             => 'course',
        self::COLLECTION             => 'collection'
    ];

    public const NOTIFICATION_IMAGE = 'notification_image';

    public $appends = ['image_url'];
    public function getImageUrlAttribute(): string
    {

        /** @var Media $media */
        $media = $this->getMedia(self::NOTIFICATION_IMAGE)->first();

        return ! empty($media) ? $media->getFullUrl() : '';
    }

    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = str_replace("||br||", "<br/>", strip_tags(str_replace("<br/>", "||br||", $value)));
    }

    public function getBodyTitleAttribute()
    {

       return str_replace("\n", "<br/>", $this->body);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = strip_tags($value);
    }
}
