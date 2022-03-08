<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class autoregaltaUsuRequest extends FormRequest
{
    public function messages() 
    {
        return [
            'nombre.min'           => 'El Nombre debe ser de mínimo 2 caracteres.',
            'nombre.max'           => 'El Nombre debe ser de mínimo 80 caracteres.',
            'nombre.required'      => 'El Nombre es obligatorio.',
            'nombre.regex'         => 'El Nombre contiene campos inválidos.',
            'paterno.min'          => 'El Apellido Paterno debe ser de mínimo 2 caracteres.',
            'paterno.max'          => 'El Apellido Paterno debe ser de mínimo 80 caracteres.',
            'paterno.required'     => 'El Apellido Paterno es obligatorio.',
            'paterno.regex'        => 'El Apellido Paterno contiene campos inválidos.',
            'materno.min'          => 'El Apellido Materno debe ser de mínimo 2 caracteres.',
            'materno.max'          => 'El Apellido Materno debe ser de mínimo 80 caracteres.',
            'materno.required'     => 'El Apellido Materno es obligatorio.',
            'materno.regex'        => 'El Apellido Materno contiene campos inválidos.',
            //'correo.email'       => 'Formato incorrecto (ejemplo@ejemplo.ejemplo).',
            //'correo.required'    => 'El e-mail es obligatorio.',
            //'usuario.email'      => 'Formato incorrecto (ejemplo@ejemplo.ejemplo).',
            'usuario.required'     => 'El e-mail es obligatorio y deberá ser una cuenta de correo electrónico activa para accesar al sistema.',
            'usuario.min'          => 'El usuario debe ser de mínimo 5 caracteres.',
            'usuario.max'          => 'El usuario debe ser de máximo 40 caracteres.',
            'password.min'         => 'La contraseña debe ser de mínimo 6 caracteres.',
            'password.max'         => 'La contraseña debe ser de máximo 30 caracteres.',
            'password.required'    => 'La contraseña es necesaria para registrarse.',
            'eq_desc.min'          => 'El Nombre del equipo de futbol debe ser de mínimo 3 caractéres.',
            'eq_desc.max'          => 'El Nombre del equipo de futbol debe ser de mínimo 100 caractéres.',
            'eq_desc.required'     => 'El Nombre del equipo de futbol es obligatorio.',
            //'eq_rama.required'   => 'Rama del equipo femenil o varonil es obligatorio.'                        
            'municipio_id.required'=> 'Municipio es obligatorio.'            
            //'unidad.required'    => 'La Unidad Administrativa es obligatoria.',
            //'perfil.required'    => 'La Unidad Administrativa es obligatoria.'
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
            'nombre'       => 'min:2|max:80|required', //|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
            'paterno'      => 'min:2|max:80|required', //|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
            'materno'      => 'min:2|max:80|required', //|regex:/(^([a-zA-z\s]+)?$)/i',
            'usuario'      => 'email|min:5|max:40|required', 
            'password'     => 'min:6|max:30|required',
            //'eq_rama'    => 'required',            
            'eq_desc'      => 'min:3|max:100|required', //|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/'
            'municipio_id' => 'required'
            //'cve_dependencia' => 'required',
            //'perfil'     => 'required'
        ];
    }
}
