<?php

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::all();
        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        return view('prescriptions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'prescription_date' => 'required|date',
            'prescribing_doctor' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        Prescription::create($request->all());

        return redirect()->route('prescriptions.index')->with('success', 'Prescription created successfully!');
    }

    public function edit(Prescription $prescription)
    {
        return view('prescriptions.edit', compact('prescription'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'prescription_date' => 'required|date',
            'prescribing_doctor' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $prescription->update($request->all());

        return redirect()->route('prescriptions.index')->with('success', 'Prescription updated successfully!');
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();

        return redirect()->route('prescriptions.index')->with('success', 'Prescription deleted successfully!');
    }
}