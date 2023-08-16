<?php

namespace App;

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Spatie\Activitylog\Traits\LogsActivity;

class GoodsReceiving extends Model
{
   
    protected $table = 'goods_received';
    public $timestamps = 'false';
    protected $fillable = ['id', 'medicine_id', 'quantity', 'unit_cost','selling_price', 'user_id','total_cost','total_profit',
        'expire_date', 'received_at',];

    public function supplier()
    {

        return $this->belongsTo('App\Supplier');
    }

    public function product()
    {

        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    public function invoice()
    {

        return $this->belongsTo(Invoice::class, 'invoice_no');
    }

}

