<?php

namespace App\Models;

use App\Models\Traits\AbortNotFound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrescriptionMedicine extends Model
{
    use HasFactory, AbortNotFound, SoftDeletes;

    protected $fillable = [
        'medicine_id',
        'frequency',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function times()
    {
        return $this->hasMany(PrescriptionMedicineTime::class);
    }
}
