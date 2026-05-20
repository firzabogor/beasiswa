<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_program_id', 
        'name',
        'start_date',
        'end_date',
        'execution_start_date',
        'execution_end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'execution_start_date' => 'date',
        'execution_end_date' => 'date',
    ];

    public function trainingProgram()
    {
        return $this->belongsTo(TrainingProgram::class);
    }
}
