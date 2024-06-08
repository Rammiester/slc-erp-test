<?php

namespace App\Repositories;

use App\Models\StudentAcademicInfo;

class StudentAcademicInfoRepository {
    public function store($request, $student_id) {
        
        
        try {
            StudentAcademicInfo::create([
                'student_id'        => $student_id,
                'semester'          => $request['semester'],
                'state_of_domicile' => $request['state_of_domicile'],
                'board_reg_no'      => $request['board_reg_no'],
                'id_card_number' => $request['id_card_number'],
                'admission_criteria' => $request['admission_criteria'],
                'id_card_number' => $request['id_card_number'],
                'exam_roll_no' => $request['exam_roll_no'],
                'state_of_domicile' => $request['state_of_domicile'],
                'apply_to_minority_colleges' => $request['apply_to_minority_colleges'],
                'kashmiri_migrant' => $request['kashmiri_migrant'],
                'board_of_education' => $request['board_of_education'],
                'persons_with_disabilities' => $request['persons_with_disabilities'],
                'annual_family_income' => $request['annual_family_income'],
                'sikkimese_students' => $request['sikkimese_students'],
                'aadhar_card_number' => $request['aadhar_card_number'],
                'j&k_students' => $request['j&k_students'],
                

                
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Student academic information. '.$e->getMessage());
        }
    }
}