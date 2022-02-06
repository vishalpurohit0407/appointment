<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Admin\ResetPassword as ResetPasswordNotification;


class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guard = 'admin';
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
	 	'name', 'username', 'email', 'password', 'profile_img', 'status'
	];
	/**
	* The attributes that should be hidden for arrays.
	*
	* @var array
	*/
	protected $hidden = [
	 	'password', 'remember_token',
	];

	public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}

