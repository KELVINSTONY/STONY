<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;

class AllocationController extends Controller
{
    public function AllocatePatient(Request $request, $user_id,$patient_id){
        $user = User::find($user_id);
        $patient = Patient::find($patient_id);

        if(!$user || !$patient){
            return response()->json(['message'=>'user/patient not found'],404);
        }
            $user->patients()->attach($patient);
            return response()->json(['message'=>'user/patient allocated']);
        }
    
}
