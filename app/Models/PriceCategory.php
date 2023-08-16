<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceCategory extends Model
{
    public $timestamps = false;
    protected $table = 'price_categories';

    public function test_prices()
    {
        return $this->hasMany(LabTestPrice::class, 'price_category_id');
    }

    public function imaging_test_prices()
    {
        return $this->hasMany(RadiologyTestPrice::class, 'price_category_id');
    }

    public function patient()
    {
        return $this->hasMany(Patient::class, 'price_category_id');
    }

    public function priceList()
    {
        return $this->hasMany(PriceList::class, 'price_category_id');
    }

    public function sale()
    {
        return $this->hasMany(Sale::class, 'price_category_id');
    }

}
