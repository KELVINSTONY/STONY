<?php

namespace Database\Seeders;
use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = Storage::disk('local')->get('/json/date.json');
        $medicines = json_decode($json, true);
        foreach ($medicines as $medicine) {
            Medicine::query()->updateOrCreate([
                'product_category' => $medicine['product_category'],
                'certificate_number' => $medicine['certificate_number'],
                'brand_name' => $medicine['brand_name'],
                'Classification' => $medicine['Classification'],
                'generic_name' => $medicine['generic_name'],
                'dosage_form' => $medicine['dosage_form'],
                'national_id_no' => $medicine['national_id_no'],
                'active_ingredients' => $medicine['active_ingredients'],
                'product_strenght' => $medicine['product_strenght'],
                'registrant' => $medicine['registrant'],
                'registrant_country' => $medicine['registrant_country'],
                'local_technical_representative' => $medicine['local_technical_representative'],
                'manufacturer' => $medicine['manufacturer'],
                'manufacturer_country' => $medicine['manufacturer_country'],
                'registration_status' => $medicine['registration_status']
            ]);
        }
    }
}
