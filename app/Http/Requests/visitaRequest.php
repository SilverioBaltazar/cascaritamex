<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class visitaRequest extends FormRequest
{
    public function messages()
    {
        return [
            'periodo2_id.required'      => 'El periodo fiscal es obligatorio.',
            'mes2_id.required'          => 'El mes es obligatorio.',
            'dia2_id.required'          => 'El dia es obligatorio.',  
            'hora2_id.required'         => 'La hora es obligatoria.',            
            'num2_id.required'          => 'Los minutos son obligatorios.',            
            'entidad2_id.required'      => 'La entidad es obligatoria.',
            'municipio2_id.required'    => 'El municipio es obligatorio.'
            //'num3_id.required'        => 'Plazo de entrega de duplicado de acta de mandamiento es obligatorio'            
            //'visita_contacto.min'     => 'El contacto de la IAP es de mínimo 1 caracter.',
            //'visita_contacto.max'     => 'El contacto de la IAP es de máximo 100 caracteres.',
            //'visita_contacto.required'=> 'El contacto de la IAP es obligatorio.',
            //'visita_dom.min'          => 'El domicilio de la IAP es de mínimo 1 caracter.',
            //'visita_dom.max'          => 'El domicilio de la IAP es de máximo 100 caracteres.',
            //'visita_dom.required'     => 'El domicilio de la IAP es obligatorio.',      
            //'visita_tel.min'          => 'El teléfono de la IAP es de mínimo 1 caracter.',
            //'visita_tel.max'          => 'El teléfono de la IAP es de máximo 60 caracteres.',
            //'visita_tel.required'     => 'El teléfono de la IAP es obligatorio.',                        
            //'visita_obj.min'          => 'El objetivo de la visita es de mínimo 1 caracter.',
            //'visita_obj.max'          => 'El objetivo de la visita es de máximo 300 caracteres.',
            //'visita_obj.required'     => 'El objetivo de la visita es obligatorio.', 
            //'visita_spub.min'         => 'El servidor público que programo visita es de mínimo 1 caracter.',
            //'visita_spub.max'         => 'El servidor público que programo visita es de máximo 60 caracteres.',
            //'visita_spub.required'    => 'El servidor público que programo visita es obligatorio.',
            //'visita_spub2.min'        => 'Personal programado en la diligencia es de mínimo 1 caracter.',
            //'visita_spub2.max'        => 'Personal programado en la diligencia es de máximo 200 caracteres.',
            //'visita_spub2.required'   => 'Personal programado en la diligencia es obligatorio.'            
            //'iap_foto1.required'      => 'La imagen es obligatoria'
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
            //'iap_desc.'    => 'required|min:1|max:100',
            //'iap_id'         => 'required',
            'periodo2_id'     => 'required',
            'mes2_id'         => 'required',
            'dia2_id'         => 'required', 
            'hora2_id'        => 'required', 
            'num2_id'         => 'required', 
            'entidad2_id'     => 'required',             
            'municipio2_id'   => 'required'
            //'num3_id'       => 'required'
            //'visita_dom'    => 'required|min:1|max:100',
            //'visita_tel'    => 'required|min:1|max:60',
            //'visita_obj'    => 'required|min:1|max:300',
            //'visita_spub'   => 'required|min:1|max:80',
            //'visita_spub2'  => 'required|min:1|max:200'
            //'accion'        => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'        => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc'    => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
