<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCategory extends Model implements HasMedia
{
    use SoftDeletes;
    use HasFactory;
    use Translatable;
    use InteractsWithMedia;
    public $fillable = [
        'status',
        'nick_name',
        'sequence',
        'created_by',
        'updated_by'

    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'visible_on_app',
        'media'
    ];

    protected $dates = ['deleted_at'];

    public $translatedAttributes = ['category_name'];

    const IMAGE= 'course_category_image';
    public const TRANSLATED_BLOCK = [
	    'category_name' => 'input'
	];
    public function categoryTranslations()
    {
        return $this->hasMany(\App\Models\CourseCategoryTranslation::class);
    }

    public $appends = ['image_url'];
    public function getImageUrlAttribute(): string
    {

        /** @var Media $media */
        $media = $this->getMedia(self::IMAGE)->first();

        return ! empty($media) ? $media->getFullUrl() : '';
    }
}
