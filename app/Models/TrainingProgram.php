<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'kategori',
        'description',
        'start_date',
        'end_date',
        'location',
        'quota',
        'status',
        'image_path',
        'document_path',
        'template_report_path',
        'template_report_2_path',
        'execution_start_date',
        'execution_end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'execution_start_date' => 'date',
        'execution_end_date'   => 'date',
    ];

    public function requirements()
    {
        return $this->hasMany(ProgramRequirement::class);
    }

    public function topics()
    {
        return $this->hasMany(ProgramTopic::class);
    }

    /**
     * Semua pendaftar program ini.
     */
    public function registrations()
    {
        return $this->hasMany(TrainingRegistration::class);
    }

    /**
     * Jumlah pendaftar yang aktif (pending/approved).
     */
    public function activeRegistrationsCount()
    {
        return $this->registrations()
            ->whereIn('status', ['pending', 'approved'])
            ->count();
    }

    /**
     * Cek apakah kuota masih tersedia.
     */
    public function hasQuota(): bool
    {
        return $this->activeRegistrationsCount() < $this->quota;
    }
}
