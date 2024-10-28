<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define a list of sample medicines
        $medicines = [
            ['name' => 'Paracetamol'],
            ['name' => 'Amoxicillin'],
            ['name' => 'Ibuprofen'],
            ['name' => 'Cetirizine'],
            ['name' => 'Omeprazole'],
            ['name' => 'Metformin'],
            ['name' => 'Ciprofloxacin'],
            ['name' => 'Loratadine'],
            ['name' => 'Aspirin'],
            ['name' => 'Dextromethorphan'],
        ];

        // Insert each medicine into the database
        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
