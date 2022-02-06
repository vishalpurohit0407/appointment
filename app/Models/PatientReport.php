<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Hashidable;

class PatientReport extends Model
{
    use Hashidable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id', 'appointment_id', 'collection_date', 'received_date','rt_pcr', 'rt_pcr_status', 'antigens', 'antigens_status', 'antigens_count'
    ];

    /**
     * Get the comments for the blog post.
     */
    public function patient()
    {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }
}
