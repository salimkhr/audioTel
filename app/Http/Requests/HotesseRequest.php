<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotesseRequest extends FormRequest
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
        if($this->segment(2)=="new")
            return ['name' => 'required|unique:hotesse','tarif_FR' => 'required','tarif_BE' => 'required','tarif_CH' => 'required','password' => 'required','passwordConf' => 'required|same:password','tel'=>array('required','regex:/^(?!06|07).*/')];
        else
            return ['name' => 'required','tel'=>array('required','regex:/^(?!06|07).*/')];
    }
}
