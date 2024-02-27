<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Astrotomic\Translatable\Translatable;


class Location extends Model implements HasMedia
{
    use SoftDeletes;
    use HasFactory;
    use Translatable;
    use InteractsWithMedia;

    public $fillable = [
        'status',
        'latitude',
        'longitude',
        'google_address',
        'visible_on_app',
        'created_by',
        'updated_by'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',

    ];

    public $translatedAttributes = ['name'];

    public const TRANSLATED_BLOCK = [
        'name' => 'input',
    ];
    public function locationTranslations()
    {
        return $this->hasMany(\App\Models\LocationTranslation::class);
    }

    public function setGoogleAddressAttribute($value)
    {
        $this->attributes['google_address'] = strip_tags($value);
    }

}
