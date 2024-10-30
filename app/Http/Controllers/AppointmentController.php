<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->get();
        $data['appointments'] = $appointments;
        return view('appointments.index', $data);
    }

    public function create()
    {
        $data['patients'] = User::where('role', 'patient')->get();
        $data['doctors'] = User::where('role', 'doctor')->get();
        return view('appointments.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required'
        ], [
            'patient_id.required' => 'Pasien harus dipilih.',
            'patient_id.exists' => 'Pasien yang dipilih tidak valid.',
            'doctor_id.required' => 'Dokter harus dipilih.',
            'doctor_id.exists' => 'Dokter yang dipilih tidak valid.',
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Tanggal tidak valid.',
            'date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'time.required' => 'Waktu harus diisi.',
        ]);

        $appointment = Appointment::create([
            'user_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'datetime' => "{$data['date']} {$data['time']}",
            'status' => 'scheduled',
        ]);

        return response()->json([
            'message' => 'Janji temu berhasil dibuat.',
            'data' => [
                'appointment' => $appointment,
            ],
        ], 201);
    }
}
