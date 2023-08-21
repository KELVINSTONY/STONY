<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Returni extends Model
{
    protected $table = 'returns';
    protected $fillable = ['sale_id','sales_detail_id',	'returned_quantity'	];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function salesDetail()
    {
        return $this->belongsTo(SalesDetail::class);
    }
    public function revokePayments(){
       return $this->hasMany(RevokedSalePayment::class, 'sale_details_id', 'id');
    }

}
