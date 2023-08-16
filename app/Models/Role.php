<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        // // Add other fields you want to allow mass assignment for
        // '_token', // Add _token to allow mass assignment
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
