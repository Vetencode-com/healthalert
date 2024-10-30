<?php

namespace App\Console\Commands;

use App\Libraries\Blitzmes;
use App\Models\MessageLog;
use App\Models\PrescriptionMedicineTime;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendConsumeMedicinesReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:consume-medicines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for upcoming consume medicine times';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->sendPrescriptionReminders();
        return 0;
    }

    private function sendPrescriptionReminders()
    {
        $currentTime = Carbon::now()->format('H:i');

        // Retrieve reminders for the exact minute
        $reminders = PrescriptionMedicineTime::with([
            'prescriptionMedicine.prescription.patient:id,name,phone',
            'prescriptionMedicine.medicine:id,name'
        ])
            ->whereHas('prescriptionMedicine', function ($query) {
                $query->whereNotNull('prescription_id');
            })
            ->whereRaw("DATE_FORMAT(time, '%H:%i') = ?", [$currentTime]) // Match current time to minute precision
            ->get()
            ->groupBy('time')
            ->map(function ($groupedTimes, $time) {
                return $groupedTimes->groupBy('prescriptionMedicine.prescription.patient.name')->map(function ($timesByPatient, $patientName) use ($time) {
                    $patientPhone = $timesByPatient->first()->prescriptionMedicine->prescription->patient->phone;

                    $medicines = $timesByPatient->map(function ($prescriptionMedicineTime) {
                        return $prescriptionMedicineTime->prescriptionMedicine->medicine->name;
                    })->unique()->values();

                    $message = $this->message();
                    $medicineList = $this->medicine_list($medicines);

                    $replacer = [
                        '{patient}' => $patientName,
                        '{time}' => $time,
                        '{medicine_list}' => $medicineList,
                    ];

                    // Send the reminder
                    $this->sendWhatsAppMessage($patientPhone, $message, $replacer);

                    return [
                        'time' => $time,
                        'patient_name' => $patientName,
                        'phone' => $patientPhone,
                        'medicines' => $medicines,
                    ];
                })->values();
            })->flatten(1);
    }

    private function medicine_list($medicines)
    {
        $medicineList = implode("\n", array_map(fn($medicine) => "- {$medicine}", $medicines->toArray()));
        return $medicineList;
    }

    private function message(): string
    {
        $message = "Halo, {patient} 👋\n"
            . "Ini adalah pengingat untuk meminum obat Anda sesuai dengan jadwal.\n\n"
            . "🕒 Waktu: {time}\n\n"
            . "Mohon minum obat berikut ini:\n"
            . "{medicine_list}\n\n"
            . "Pastikan untuk meminumnya sesuai dengan waktu yang telah ditentukan.\n\n"
            . "Semoga lekas sembuh!";

        return $message;
    }


    private function sendWhatsAppMessage($phone, $message, $replacer)
    {
        Blitzmes::init();
        $response = Blitzmes::sendMessage($phone, $message, $replacer);

        if ($response->http_status == 400) {
            Log::error($response->error);
        }

        MessageLog::create([
            'http_status' => $response->http_status,
            'http_message' => $response->message,
            'message' => $response->formatted_message,
        ]);
    }
}
