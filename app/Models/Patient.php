<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'date_of_birth', 'gender', 'contact_number', 'email', 'user_id'];

    // Relationships
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
