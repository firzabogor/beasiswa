<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_registration_id',
        'company_name',
        'company_address',
        'applied_position',
        'applied_at',
        'status',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    public function trainingRegistration()
    {
        return $this->belongsTo(TrainingRegistration::class);
    }

    public function selectionDocument()
    {
        return $this->hasOne(SelectionDocument::class);
    }
}
