<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 5 sample patient users
        $patients = [
            ['name' => 'Wildan M Zaki', 'phone' => '6289619925691', 'email' => 'budi@example.com'],
            ['name' => 'Iqbal Rizan', 'phone' => '6281214495074', 'email' => 'ayu@example.com'],
            ['name' => 'Rina Kurnia', 'phone' => '6281234567892', 'email' => 'rina@example.com'],
            ['name' => 'Dedi Pratama', 'phone' => '6281234567893', 'email' => 'dedi@example.com'],
            ['name' => 'Haifa Rahma', 'phone' => '6289655463423', 'email' => 'sari@example.com'],
        ];

        foreach ($patients as $patient) {
            $last4 = substr($patient['phone'], -4);
            User::create([
                'name' => $patient['name'],
                'phone' => $patient['phone'],
                'email' => $patient['email'],
                'role' => 'patient',
                'password' => Hash::make($last4 . $last4), // Make sure to hash the password
            ]);
        }

        // Create 2 sample doctor users
        $doctors = [
            ['name' => 'Dr. Hafidz Siregar', 'phone' => '6289619925692', 'email' => 'andi@example.com'],
            ['name' => 'Dr. Sari Sulastri', 'phone' => '6281234567896', 'email' => 'maya@example.com'],
        ];

        foreach ($doctors as $doctor) {
            $last4 = substr($doctor['phone'], -4);
            User::create([
                'name' => $doctor['name'],
                'phone' => $doctor['phone'],
                'email' => $doctor['email'],
                'role' => 'doctor',
                'password' => Hash::make($last4 . $last4), // Make sure to hash the password
            ]);
        }
    }
}
