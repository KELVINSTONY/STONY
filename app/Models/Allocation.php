<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;
    protected $table = 'allocation_users';
    protected $fillable = ['user_id','patient_id','allocated','status'];

    public function patients(){
        $this->hasOne(Patient::class,);
    }

    public function user(){
        $this->hasMany(User::class);
    }
}
