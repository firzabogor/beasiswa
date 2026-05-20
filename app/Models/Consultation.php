<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tujuan',
        'pimpinan',
        'alasan',
        'jadwal',
        'status',
        'tanggapan',
        'ditanggapi_pada',
    ];

    protected $casts = [
        'jadwal' => 'datetime',
        'ditanggapi_pada' => 'datetime',
    ];

    /**
     * Konsultasi dimiliki oleh seorang user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
