<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->segment(3)=="new")
            return ['code' => 'required|integer|unique:code','pseudo'=> 'required','age'=> 'required'];
        else
            return [];
    }
}
