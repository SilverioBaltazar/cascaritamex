<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class catalogosinmueblesedoRequest extends FormRequest
{
    public function messages()
    {
        return [
            'inm_id.required' => 'La clave del inmueble social es obligatoria.',
            'inm_desc.required' => 'El nombre del inmueble social es obligatorio.',
            'inm_desc.min' => 'El nombre del inmueble social es de mínimo 1 caracteres.',
            'inm_desc.max' => 'El nombre del inmueble social es de máximo 50 caracteres.',
            'inm_desc.required' => 'El nombre del inmueble social es obligatorio.',
            'inm_desc.regex' => 'El nombre del inmueble social contiene caracteres inválidos.'
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
            'inm_desc' =>  'min:1|max:50|required'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
