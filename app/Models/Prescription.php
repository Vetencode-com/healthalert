<?php

namespace App\Models;

use App\Models\Traits\AbortNotFound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory, AbortNotFound;

    protected $fillable = [
        'user_id',
    ];

    public function medicines()
    {
        return $this->hasMany(PrescriptionMedicine::class);
    }
}
