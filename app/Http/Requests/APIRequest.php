<?php
/**
 * Created by PhpStorm.
 * User: salim
 * Date: 19/09/17
 * Time: 16:25
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class APIRequest extends FormRequest
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
            return ['cle' => 'required|unique:API'];
        else
            return ['cle' => 'required'];
    }
}