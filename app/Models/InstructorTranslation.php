<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'instructor_id',
        'name',
        'about',
        'status'

    ];
    public function instructor()
    {
        return $this->belongsTo(\App\Models\Instructor::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strip_tags($value);
    }

    public function setAboutAttribute($value)
    {
        $this->attributes['about'] = str_replace("||br||", "<br/>", strip_tags(str_replace("<br/>", "||br||", $value)));
    }
}
