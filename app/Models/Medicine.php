<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $table = 'medicines';
    protected $fillable = ['product_category', 'certificate_number', 'brand_name', 'Classification',
     'generic_name','dosage_form','national_id_no','active_ingredients','product_strenght','registrant',
     'registrant_country','local_technical_representative','manufacturer','manufacturer_country','registration_status'];

    // Relationships
    // public function prescriptionItems()
    // {
    //     return $this->hasMany(PrescriptionItem::class);
    // }

    // public function stock()
    // {
    //     return $this->hasOne(Stock::class);
    // }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
