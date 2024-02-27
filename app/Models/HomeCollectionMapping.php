<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCollectionMapping extends Model
{
    use HasFactory;

    public $table = 'home_collection_mappings';
    public $fillable = [
        'title',
        'home_collection_id',
        'mapped_ids',
        'sequence',
        'is_clickable',
        'mapped_to',
    ];

    const COURSE = 'Course';

    const MAPPING_COLLECTION_TYPES = [
        self::COURSE   => 'Course'
    ];
}
