<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'booked_course_id',
        'payment_mode',
        'payment_status',
        'amount',
        'transaction_date',
        'remark'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
