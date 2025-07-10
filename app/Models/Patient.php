<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

   protected $fillable = [
    'first_name',
    'last_name',
    'gender',
    'age',
    'profile_photo',
    'registration_date',        // 🆕 مهم جدًا
    'condition_percentage',     // 🆕 لو هتستخدميه في العرض
    'condition_description'     // 🆕 نفس الشيء
  ];


    // ✅ العلاقة بين المريض والتشخيصات
    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }
}
