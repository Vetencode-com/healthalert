<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionMedicine;
use App\Models\User;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        return view('prescriptions.index');
    }

    public function create()
    {
        $data['patients'] = User::where('role', 'patient')->get();

        // $data['registeredMedicines'] = PrescriptionMedicine::with('medicine')->whereNull('prescription_id')->get();
        $data['registeredMedicines'] = PrescriptionMedicine::select('prescription_medicines.*', 'medicines.name') // add specific medicine columns you need
            ->whereNull('prescription_id')
            ->leftJoin('medicines', 'prescription_medicines.medicine_id', '=', 'medicines.id')
            ->get();

        $registeredMedicineIDs = [];
        foreach ($data['registeredMedicines'] as $medicine) {
            $registeredMedicineIDs[] = $medicine->medicine_id;
        }
        $data['medicines'] = Medicine::whereNotIn('id', $registeredMedicineIDs)->get();

        return view('prescriptions.create', $data);
    }

    public function store(Request $request)
    {
        $prescription = Prescription::create([
            'user_id' => $request->patient_id,
        ]);

        PrescriptionMedicine::whereNull('prescription_id')->update(['prescription_id' => $prescription->id]);

        return response()->json([
            'success' => true,
            'message' => 'Resep berhasil dibuat',
            'data' => [
                'prescription' => $prescription,
            ],
        ]);
    }
}
