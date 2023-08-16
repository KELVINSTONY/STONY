<?php

namespace App;

use App\Category as category;
use App\CurrentStock as currentStock;
use App\OrderDetail as orderDetail;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'inv_products';
    protected $fillable = ['name'];

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id')->withDefault();
    }

    public function currentStock()
    {
        return $this->hasMany(currentStock::class, 'product_id');
    }

    public function orderDetail()
    {
        return $this->hasMany(orderDetail::class, 'product_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    public function stockTransfer()
    {
        return $this->hasMany(StockTransfer::class, 'product_id');
    }

    public function incomingStock()
    {
        return $this->hasMany(GoodsReceiving::class, 'product_id');
    }

    public function prescriptionDetail()
    {
        return $this->hasMany(PrescriptionDetail::class, 'product_id');
    }

    public function salesDetail()
    {
        return $this->hasMany(SalesDetail::class, 'product_id');
    }

    public function nhifServices()
    {
        return $this->hasMany(NHIFService::class, 'item_code', 'nhif_code');
    }
}
