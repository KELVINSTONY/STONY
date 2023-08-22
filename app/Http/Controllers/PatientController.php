<?php

namespace App\Http\Controllers;
use App\Models\Allocation;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::all();
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }


    public function allocatePatient($id)
    {
        $user = User::all();
        $patient = Patient::find($id);
        return view('allocation.create', compact('user','patient'));
    }
    public function storeAllocation (Request $request){
       $validation =  $request->validate(['user_id' => 'required',
            'patient_id'=>'required']);
       $allocated =  Allocation::firstOrNew([
         'patient_id' =>$validation['patient_id'],
         'allocated' => $validation['user_id'],
         'user_id' => Auth::user()->id,
          'status' => 1
        ]);
       $allocated->save();
        return redirect()->route('dashboard')->with('success', 'Patient allocated successfully!');
    }
    public function patientAllocated (){
        $user = Auth::user()->id;
        $eager = Allocation::where('allocated',$user)->get();
        $allocated = Allocation::all();
        dd($allocated);
    }
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);
        Patient::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Patient created successfully!');
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $patient->update($request->all());

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully!');
    }
}
