<?php

namespace App\Http\Controllers;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::all();
        return view('medicines.index', compact('medicines'));
    }
    

    public function create()
    {
        return view('medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'dosage_form' => 'nullable|string',
            'generic_name' => 'nullable|string',
            'active_ingredients' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
        ]);

        Medicine::create($request->all());

        return redirect()->route('medicines.index')->with('success', 'Medicine created successfully!');
    }
    public function show(Medicine $medicine)
        
    {
        return view('medicines.show', compact('medicine'));
    }


    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {  
       
        // $request->validate([
        //     'brand_name' => 'required|string|max:255',
        //     'dosage_form' => 'nullable|string',
        //     'generic_name' => 'nullable|string',
        //     'active_ingredients' => 'nullable|string|max:255',
        //     'manufacturer' => 'nullable|string|max:255',
        // ]);
        $medicine->update($request->all());

        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully!');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully!');
    }
}
