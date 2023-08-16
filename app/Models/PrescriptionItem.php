<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = ['prescription_id', 'medicine_id', 'quantity', 'dosage_instructions', 'user_id'];

    // Relationships
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    // public function medicine()
    // {
    //     return $this->belongsTo(Medicine::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
