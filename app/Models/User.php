<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class User extends Authenticatable implements JWTSubject, HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia;

    const IMAGE= 'user_image';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'gender',
        'login_allowed',
        'admin_remark',
        'is_verified',
        'approval_status',
    ];

    protected $casts = [
        'id'                    => 'integer',
        'email'                 =>'string',
        'gender'                => 'string',
        'whatsapp_no'           => 'string',
        'city'                  => 'string',
        'created_by'            => 'integer',
        'updated_by'            => 'integer',
        'phone_verified_at'     => 'datetime',
        'last_login'            => 'datetime',
        'whatsapp_notification' => 'boolean',
        'email_notification'    => 'boolean',
        'sms_notification'      => 'boolean',
        'password_allowed'      => 'boolean',
        'otp_allowed'           => 'boolean',
        'login_allowed'         => 'boolean',
        'admin_remark'          => 'string',
        'approved_by'           => 'integer',
        'approved_on'           => 'datetime',
        'approval_status'       => 'string',
        'status'                => 'boolean',
        'fcm_notification'      => 'boolean'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at',
        'password',
        'whatsapp_no',
        'city',
        'created_by',
        'updated_by',
        'updated_at',
        'phone_verified_at',
        'is_verified',
        'last_login',
        'fpwd_flag',
        'whatsapp_notification',
        'email_notification',
        'sms_notification',
        'password_allowed',
        'otp_allowed',
        'login_allowed',
        'admin_remark',
        'approved_by',
        'approved_on',
        'approval_status',
        'status',
        'email_verified_at',
        'media'
    ];

    public $appends = ['user_image'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userDevices()
    {
        return $this->hasMany(UserDevice::class,'user_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function bookedCourse()
    {
        return $this->hasMany(BookedCourse::class);
    }

    public function newBookedCourse()
    {
        return $this->hasOne(BookedCourse::class)->orderBy('created_at','desc');
    }

    public function courses()
    {
        return $this->hasManyThrough(Course::class, BookedCourse::class, 'user_id', 'id', 'id', 'course_id');
    }

    public function getUserImageAttribute(): string
    {

        /** @var Media $media */
        $media = $this->getMedia(self::IMAGE)->first();

        return ! empty($media) ? $media->getFullUrl() : config('global.static_base_url')."/backend/default_image/profile.png";
    }

}
