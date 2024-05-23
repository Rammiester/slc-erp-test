<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoticeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Check if the user has the 'create notices' permission
        // or if they have the role of 'teacher'
        return $this->user()->can('create notices') || $this->user()->hasRole('teacher');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'notice'    => 'required',
            'session_id'=> 'required',
            'audience'=> 'required',
        ];
    }
}
