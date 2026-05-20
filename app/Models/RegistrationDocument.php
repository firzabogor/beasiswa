<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_registration_id',
        'program_requirement_id',
        'document_path',
    ];

    public function registration()
    {
        return $this->belongsTo(TrainingRegistration::class, 'training_registration_id');
    }

    public function requirement()
    {
        return $this->belongsTo(ProgramRequirement::class, 'program_requirement_id');
    }
}
