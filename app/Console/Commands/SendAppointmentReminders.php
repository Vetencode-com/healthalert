<?php

namespace App\Console\Commands;

use App\Libraries\Blitzmes;
use App\Libraries\Formatter;
use App\Models\Appointment;
use App\Models\MessageLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp reminders for upcoming appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $datesToCheck = [
            $now->copy()->addDays(3)->startOfDay(), // H-3
            $now->copy()->addDays(2)->startOfDay(), // H-2
            $now, // Exact time
        ];

        foreach ($datesToCheck as $date) {
            $this->sendRemindersForDate($date);
        }

        return 0;
    }

    private function sendRemindersForDate(Carbon $date)
    {
        $appointments = Appointment::whereDate('datetime', $date->toDateString())
            ->whereTime('datetime', '<=', $date->toTimeString())
            ->where('status', 'scheduled')
            ->get();

        foreach ($appointments as $appointment) {
            // Send WhatsApp message here
            $this->sendWhatsAppMessage($appointment);
        }
    }

    private function sendWhatsAppMessage(Appointment $appointment)
    {
        $patient = $appointment->patient;
        $doctor = $appointment->doctor;
        $date = Formatter::commonDate($appointment->datetime);
        $time = Formatter::shortTime($appointment->datetime);

        $message = $this->message();

        Blitzmes::init();
        $response = Blitzmes::sendMessage($patient->phone, $message, [
            '{patient}' => $patient->name,
            '{doctor}' => $doctor->name,
            '{date}' => $date,
            '{time}' => $time,
        ]);

        if ($response->http_status == 400) {
            Log::error($response->error);
        }

        MessageLog::create([
            'http_status' => $response->http_status,
            'http_message' => $response->message,
            'message' => $response->formatted_message,
        ]);

        $this->info("WhatsApp reminder sent to {$patient->name} for appointment with {$doctor->name} on {$appointment->datetime}");
    }

    private function message(): string
    {
        $message = "Halo, {patient} 👋\n"
            . "Ini adalah pengingat untuk janji temu Anda dengan {doctor}.\n\n"
            . "📅 Tanggal: {date}\n"
            . "🕒 Waktu: {time}\n\n"
            . "Silakan hadir tepat waktu dan siapkan segala hal yang diperlukan.\n"
            . "Jika Anda perlu membatalkan atau mengubah jadwal, hubungi kami sesegera mungkin.\n\n"
            . "Terima kasih, dan sampai jumpa!";

        return $message;
    }
}
