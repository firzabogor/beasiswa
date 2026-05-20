<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_registration_id',
        'semester_name',
        'document_path',
        'notes',
    ];

    public function trainingRegistration()
    {
        return $this->belongsTo(TrainingRegistration::class);
    }
}
