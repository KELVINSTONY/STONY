<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_id', 'quantity_in_stock', 'user_id'];

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
