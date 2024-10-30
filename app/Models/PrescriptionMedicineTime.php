<?php

namespace App\Models;

use App\Models\Traits\AbortNotFound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionMedicineTime extends Model
{
    use HasFactory, AbortNotFound;

    protected $fillable = [
        'prescription_medicine_id',
    ];

    public function medicine_from_prescription()
    {
        return $this->belongsTo(PrescriptionMedicine::class);
    }
}
