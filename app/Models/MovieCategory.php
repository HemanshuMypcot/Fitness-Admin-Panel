<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MovieCategory extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    public $fillable = [
        'category',
        'name',
        'status',
        'season',
        'yt_link',
        'genre',
];
    protected $hidden =[
        'media'
    ];
    const IMAGE= 'movie_category_image';

}
