<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class catalogostrxRequest extends FormRequest
{
    public function messages()
    {
        return [
            'trx_desc.min' => 'El nombre de la función es de mínimo 1 caracteres.',
            'trx_desc.max' => 'El nombre de la función es de máximo 100 caracteres.',
            'trx_desc.required' => 'El nombre de la función es obligatorio.'
            //'trx_desc.regex' => 'El nombre de la función contiene caracteres inválidos.'
        ];
    }
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
        return [
            'trx_desc' => 'required|min:1|max:100'
            //'trx_desc' => 'min:1|max:100|required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i'
        ];
    }
}
