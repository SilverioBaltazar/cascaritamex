<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class emailbienveRequest extends FormRequest
{
    public function messages() 
    {
        return [
            'login'               => 'Correo electrónico (ejemplo@ejemplo.ejemplo).'
            //'perfil.required'   => 'La Unidad Administrativa es obligatoria.'
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
            //'nombre'    => 'min:4|max:80|required|regex:/(^([a-zA-z\s]+)?$)/i',
            'login'      => 'min:4|max:80|required'   //|regex:/(^([a-zA-z\s]+)?$)/i',
            //'materno'   => 'min:4|max:80|required|regex:/(^([a-zA-z\s]+)?$)/i',
            //'usuario'   => 'email|min:5|max:40|required',
            //'password'  => 'min:6|max:30|required'
            //'cve_arbol' => 'required',
            //'cve_dependencia' => 'required',
            //'perfil'    => 'required'
        ];
    }
}
