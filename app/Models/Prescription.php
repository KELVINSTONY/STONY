<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'prescription_date', 'prescribing_doctor', 'remarks', 'user_id'];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
