<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'email'             => 'required|string|email|max:255|unique:users',
            'gender'            => 'required|string',
            'nationality'       => 'required|string',
            'phone'             => 'required|string',
            'address'           => 'required|string',
            'address2'          => 'nullable|string',
            'city'              => 'required|string',
            'zip'               => 'required|string',
            'photo'             => 'nullable|string',
            'birthday'          => 'required|date',  
            'id_card_number'    => 'required|string',
            'board_reg_no'          => 'required|string',  //new //done
            'admission_criteria'        => 'required|string', //new //done
            'password'          => 'required|string|min:8',
            // 'id_card_number'    => 'required',
            // Parents' information
            'father_name'       => 'required|string',
            'father_phone'      => 'required|string',
            'mother_name'       => 'required|string',
            'mother_phone'      => 'required|string',
            'parent_address'    => 'required|string',

            // Academic information
            'class_id'          => 'required',
            'section_id'        => 'required',
            'id_card_number'      => 'required|string',     //new //done
            'session_id'        => 'required',
            
            'exam_roll_no'    => 'required|string', //new //done
            'state_of_domicile' => 'required|string', //new //done 
            'apply_to_minority_colleges'    => 'required|string', //new //done
            'kashmiri_migrant'  => 'required|string', //new //done
            'board_of_education'    => 'required|string', //new //done
            'persons_with_disabilities' => 'required|string', //new //done
            'annual_family_income' => 'required|string', //new //done
            'sikkimese_students' => 'required|string', //new //done
            'aadhar_card_number' => 'required|string', //new //done
            'j&k_students' => 'required|string', //new  //done
            'semester' => 'required|string', //new //done

        ];
    }
}
