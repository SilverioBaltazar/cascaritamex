<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class catalogosRequest extends FormRequest
{
    public function messages()
    {
        return [
            'proceso_desc.min' => 'El nombre del proceso es de mínimo 1 caracteres.',
            'proceso_desc.max' => 'El nombre del proceso es de máximo 100 caracteres.',
            'proceso_desc.required' => 'El nombre del proceso es obligatorio.'
            //'proceso_desc.regex' => 'El nombre del proceso contiene caracteres inválidos.'
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
            'proceso_desc' => 'required|min:1|max:100'
            //'proceso_desc' => 'min:1|max:100|required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i'
        ];
    }
}
