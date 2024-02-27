<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
        'location_id',
        'locale'
    ];


    public function location()
    {
        return $this->belongsTo(App\Models\Location::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strip_tags($value);
    }

}
