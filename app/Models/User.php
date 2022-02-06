<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Http\Traits\Hashidable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Storage;

class User extends Authenticatable
{
        use Notifiable, Hashidable,SoftDeletes,HasApiTokens;
        use HasFactory;

        protected $appends = ['profile_pic_url'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'provider_name',
        'email',
        'password',
        'mobile',
        'profile_pic',
        'status',
        'address',
        'user_type',
        'dob',
        'gender',
        'erp_id',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_type' => 'integer'

    ];

    public function getProfilePicUrlAttribute()
    {
            return (isset($this->profile_pic) && Storage::disk(env('FILESYSTEM_DRIVER'))->exists($this->profile_pic) ? Storage::disk(env('FILESYSTEM_DRIVER'))->url($this->profile_pic) : asset('assets/media/users/blank.png'));
    }
}
