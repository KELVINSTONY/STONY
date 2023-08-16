<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receive extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_id', 'quantity_received', 'receive_date', 'user_id'];

    // Relationships
    // public function medicine()
    // {
    //     return $this->belongsTo(Medicine::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
