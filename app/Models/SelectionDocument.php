<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectionDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_application_id',
        'document_path',
        'selection_result',
        'notes',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function companyApplication()
    {
        return $this->belongsTo(CompanyApplication::class);
    }
}
