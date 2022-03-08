<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
 
class fichaRequest extends FormRequest
{
    public function messages() 
    {
        return [
            'eq_desc.required'       => 'El nombre equipo es obligatorio.',
            'eq_desc.min'            => 'El nombre del equipo es de mínimo 1 caracter.',
            'eq_desc.max'            => 'El nombre del equipo es de máximo 100 caracteres.',
            'eq_nombres_rep.required'=> 'El nombre(s) del representante del equipo es obligatorio.',
            'eq_nombres_rep.min'     => 'El nombre(s) del representante del equipo es de mínimo 1 caracter.',
            'eq_nombres_rep.max'     => 'El nombre(s) del representante del equipo es de máximo 80 caracteres.',
            'eq_ap_rep.required'     => 'El apellido paterno del representante del equipo es obligatorio.',
            'eq_ap_rep.min'          => 'El apellido paterno del representante del equipo es de mínimo 1 carater.',
            'eq_ap_rep.max'          => 'El apellido paterno del representante del equipo es de máximo 80 carateres.',
            'eq_tel_rep.required'    => 'El teléfono es obligatorio y digitar soló numeros preferentemente.',
            'eq_tel_rep.min'         => 'El teléfono es de mínimo 1 caracteres númericos preferentemente.',
            'eq_tel_rep.max'         => 'El teléfono es de máximo 60 caracteres numéricos prefentemente.',
            'eq_email_rep.required'  => 'El correo eléctronico es obligatorio.',
            'eq_email_rep.min'       => 'El correo eléctronico es de mínimo 1 caracter.',
            'eq_email_rep.max'       => 'El correo eléctronico es de máximo 80 caracteres.',
            'municipio_id.required'  => 'El municipio es obligatorio.',            
            'cate_id.requered'       => 'La categoria es obligatoria.',
            'eq_rama.required'       => 'La rama Varonil o femenil es obligatoria.'
            //'eq_foto1.required'    => 'La imagen es obligatoria'
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
            'eq_desc'       => 'required|min:1|max:100',
            'eq_nombres_rep'=> 'required|min:1|max:80',
            'eq_ap_rep'     => 'required|min:1|max:80',            
            'eq_tel_rep'    => 'required|min:1|max:60',
            'eq_email_rep'  => 'required|email|min:1|max:80',            
            'eq_rama'       => 'required',       
            'municipio_id'  => 'required',            
            'cate_id'       => 'required'            
            //'rubro_desc'   => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
