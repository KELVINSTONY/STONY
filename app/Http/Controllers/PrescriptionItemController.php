<?php

use App\Http\Controllers\Controller;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;

class PrescriptionItemController extends Controller
{
    public function index()
    {
        $prescriptionItems = PrescriptionItem::all();
        return view('prescription_items.index', compact('prescriptionItems'));
    }

    public function create()
    {
        return view('prescription_items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'dosage_instructions' => 'nullable|string',
        ]);

        PrescriptionItem::create($request->all());

        return redirect()->route('prescription_items.index')->with('success', 'Prescription item created successfully!');
    }

    public function edit(PrescriptionItem $prescriptionItem)
    {
        return view('prescription_items.edit', compact('prescriptionItem'));
    }

    public function update(Request $request, PrescriptionItem $prescriptionItem)
    {
        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'dosage_instructions' => 'nullable|string',
        ]);

        $prescriptionItem->update($request->all());

        return redirect()->route('prescription_items.index')->with('success', 'Prescription item updated successfully!');
    }

    public function destroy(PrescriptionItem $prescriptionItem)
    {
        $prescriptionItem->delete();

        return redirect()->route('prescription_items.index')->with('success', 'Prescription item deleted successfully!');
    }
}