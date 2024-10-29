<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PrescriptionMedicine;
use App\Models\PrescriptionMedicineTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionMedicineController extends Controller
{
    // add new medicine to recipes
    public function store(Request $request)
    {
        $medicineItem = PrescriptionMedicine::create([
            'medicine_id' => $request->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Obat ditambahkan',
            'data' => [
                'id' => $medicineItem->id,
                'medicine' => $medicineItem->medicine,
            ]
        ]);
    }

    // delete medicine
    public function delete($id)
    {
        $medicineItem = PrescriptionMedicine::findOrAbort($id);
        $medicineItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Obat dihapus',
            'data' => [
                'medicine' => $medicineItem->medicine,
            ],
        ]);
    }

    // change frequncy
    public function change_frequency(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $medicineItem = PrescriptionMedicine::findOrAbort($id);

                $frequency = $request->frequency;
                $medicineItem->frequency = $frequency;
                $medicineItem->save();

                $total_times = $medicineItem->times()->count();

                // If the current times are fewer than the requested frequency
                if ($total_times < $frequency) {
                    $newTimes = [];
                    for ($i = 0; $i < ($frequency - $total_times); $i++) {
                        $newTimes[] = [
                            'prescription_medicine_id' => $medicineItem->id,
                            'created_at' => now(),
                        ];
                    }

                    PrescriptionMedicineTime::insert($newTimes);
                } else if ($total_times > $frequency) {
                    $excessCount = $total_times - $frequency;

                    PrescriptionMedicineTime::where('prescription_medicine_id', $medicineItem->id)
                        ->orderBy('created_at', 'desc')
                        ->take($excessCount)
                        ->delete();
                }
            });

            $times = PrescriptionMedicineTime::where('prescription_medicine_id', $id)
                ->get()
                ->map(function ($time) {
                    $time->time = $time->time ? date('H:i', strtotime($time->time)) : null;
                    $time->url = route('prescriptions.medicines.times.change', $time->id);
                    return $time;
                });

            return response()->json([
                'success' => true,
                'message' => 'Dosis harian berhasil diubah',
                'data' => [
                    'times' => $times,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Error:' . $e->getMessage(),
            ], 500);
        }
    }

    public function change_time(Request $request, $id)
    {
        $time = PrescriptionMedicineTime::findOrAbort($id);

        $time->time = $request->time;
        $time->order = $request->order;
        $time->save();

        return response()->json([
            'success' => true,
            'message' => 'Waktu diperbarui',
            'data' => [
                'time' => $time,
            ],
        ]);
    }
}
