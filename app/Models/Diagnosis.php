<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'image_path',
        'predicted_class',
        'description',
        'analysis',
        'detailed_analysis',
        'recommendations',
    ];

    // العلاقة العكسية: التشخيص ينتمي إلى مريض
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
