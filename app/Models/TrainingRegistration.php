<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'training_program_id',
        'program_topic_id',
        'status',
        'registered_at',
        'verification_status',
        'verification_notes',
        'invitation_letter_path',
        'invitation_letter_uploaded_at',
        'log_path',
        'loa_path',
        'selection_final_result',
        'selection_final_doc_path',
        'final_doc_path',
        'is_education_completed',
        'final_report_path',
    ];

    protected $casts = [
        'registered_at'                => 'datetime',
        'invitation_letter_uploaded_at' => 'datetime',
    ];
    public function requirementDocuments()
    {
        return $this->hasMany(RegistrationDocument::class);
    }

    public function educationActivities()
    {
        return $this->hasMany(EducationActivity::class);
    }

    /**
     * Registrasi milik seorang user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registrasi terkait dengan satu program pelatihan.
     */
    public function trainingProgram()
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function programTopic()
    {
        return $this->belongsTo(ProgramTopic::class);
    }

    public function companyApplication()
    {
        return $this->hasOne(CompanyApplication::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    public function hasInvitationLetter(): bool
    {
        return !is_null($this->invitation_letter_path);
    }
}
