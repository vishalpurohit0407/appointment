<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Hashidable;

class PatientAppointment extends Model
{
    use Hashidable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id', 'appointment_time'
    ];

    /**
     * Get the comments for the blog post.
     */
    public function patient()
    {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }

    /**
     * Get the comments for the blog post.
     */
    public function report()
    {
        return $this->hasOne(PatientReport::class, 'appointment_id', 'id');
    }
}
