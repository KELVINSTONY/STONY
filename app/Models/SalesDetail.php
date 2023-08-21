<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    protected $table = 'sale_items';
    protected $fillable = ['sale_id','medicine_id',
    'quantity','unit_price','subtotal'];
    
    public $timestamps = false;


   

//    public function sale_return()
//    {
//        return $this->hasOne(SalesReturn::class,'sale_detail_id','id');
//    }

    public function currentStock()
    {
    	return $this->belongsTo(CurrentStock::class, 'stock_id');
    }

    
    public function revokePayments(){
       return $this->hasMany(RevokedSalePayment::class, 'sale_details_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

}
