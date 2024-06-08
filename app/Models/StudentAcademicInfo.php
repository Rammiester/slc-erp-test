<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentAcademicInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'board_reg_no',
        'student_id',
        'semester',
        'state_of_domicile',
        'board_reg_no',
        'id_card_number',
        'admission_criteria',
        'id_card_number',
        'exam_roll_no',
        'state_of_domicile',
        'apply_to_minority_colleges',
        'kashmiri_migrant',
        'board_of_education',
        'persons_with_disabilities',
        'annual_family_income',
        'sikkimese_students',
        'aadhar_card_number',
        'j&k_students',
    ];

    /**
     * Get the sections for the blog post.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
