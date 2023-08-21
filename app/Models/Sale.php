<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Medicine;
use App\Models\SalesDetail;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_id','user_id', 'quantity', 'total_amount'];

    // Relationships
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }


    public function saleDetails()
    {
        return $this->hasMany(SalesDetail::class, 'sale_id');
    }
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'id');
    }

}
