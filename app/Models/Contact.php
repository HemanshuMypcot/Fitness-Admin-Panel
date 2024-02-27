<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'message'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $rules = [
        'name' => 'required|string|max:150',
        'email' => 'required|email',
        'message' => 'required|string',
    ];
}
