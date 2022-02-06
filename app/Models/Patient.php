<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Hashidable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Storage;

class Patient extends Authenticatable
{
    use Notifiable, Hashidable,SoftDeletes,HasApiTokens;
    use HasFactory;

    protected $appends = ['user_type','profile_pic_url'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cust_id',
        'name',
        'email',
        'password',
        'mobile',
        'profile_pic',
        'status',
        'address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getUserTypeAttribute()
    {
        return (int)'2'; // 2 = Patient
    }

    public function getProfilePicUrlAttribute()
    {
            return (isset($this->profile_pic) && Storage::disk(env('FILESYSTEM_DRIVER'))->exists($this->profile_pic) ? Storage::disk(env('FILESYSTEM_DRIVER'))->url($this->profile_pic) : asset('assets/media/users/blank.png'));
    }
}
