<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
 
class oscRequest extends FormRequest
{
    public function messages()
    {
        return [
            //'osc_desc.required'    => 'El nombre de la OSC es obligatorio.',
            'osc_desc.min'           => 'El nombre de la OSC es de mínimo 1 caracter.',
            'osc_desc.max'           => 'El nombre de la OSC es de máximo 100 caracteres.',
            'osc_dom1.required'      => 'El domicilio legal es obligatorio.',
            'osc_dom1.min'           => 'El domicilio legal es de mínimo 1 caracter.',
            'osc_dom1.max'           => 'El domicilio legal es de máximo 150 caracteres.',
            //'osc_calle.required'   => 'La calle es obligatoria.',
            //'osc_calle.min'        => 'La calle es de mínimo 1 caracter.',
            //'osc_calle.max'        => 'La calle es de máximo 100 caracteres.',            
            //'osc_num.required'     => 'El número exterior y/o interior es obligatorio.',
            //'osc_num.min'          => 'El número exterior y/o interior es de mínimo 1 carater.',
            //'osc_num.max'          => 'El número exterior y/o interior es de máximo 30 carateres.',
            //'osc_colonia.required' => 'La colonia es obligatoria.',
            //'osc_colonia.min'      => 'La colonia es de mínimo 1 caracter.',
            //'osc_colonia.max'      => 'La colonia es de máximo 100 caracteres.',
            'entidadfederativa_id.required' => 'La entidad federativa es obligatoria.',
            'municipio_id.required'  => 'El municipio es obligatorio.',            
            'rubro_id.requered'      => 'El rubro es obligatorio.',
            'osc_regcons.required'   => 'El folio de registro de la propiedad es obligatorio.',
            'osc_regcons.min'        => 'El folio de registro de la propiedad es de de mínimo 1 caracter.',
            'osc_regcons.max'        => 'El folio de registro de la propiedad es de máximo 50 caracteres.',
            //'osc_rfc.required'     => 'El RFC es obligatorio.',
            //'osc_rfc.min'          => 'El RFC es de mínimo 3 caracteres.',
            //'osc_rfc.max'          => 'El RFC es de máximo 18 caracteres',
            //'osc_cp.required'      => 'El Código postal es obligatorio.',
            //'osc_cp.min'           => 'El Código postal es de mínimo 5 caracteres.',
            //'osc_cp.max'           => 'El Código postal es de máximo 5 caracteres.',
            //'osc_cp.numeric'       => 'El Código postal debe ser numerico.',            
            //'osc_feccons.requered' => 'La fecha de constitución es obligatoria dd/mm/aaaa.',
            //'inm_id.required'      => 'Estado del inmueble es obligatorio.',            
            'anio_id.requered'       => 'La vigencia en años es obligatoria.',
            //'osc_fvp.requered'     => 'La vigencia fecha de vencimiento es obligatoria dd/mm/aaaa.',            
            'osc_telefono.required'  => 'El teléfono es obligatorio y digitar soló numeros preferentemente.',
            'osc_telefono.min'       => 'El teléfono es de mínimo 1 caracteres númericos preferentemente.',
            'osc_telefono.max'       => 'El teléfono es de máximo 60 caracteres numéricos prefentemente.',
            'osc_email.required'     => 'El correo eléctronico es obligatorio.',
            'osc_email.min'          => 'El correo eléctronico es de mínimo 1 caracter.',
            'osc_email.max'          => 'El correo eléctronico es de máximo 150 caracteres.',
            'osc_pres.required'      => 'El nombre del presidente es obligatorio.',
            'osc_pres.min'           => 'El nombre del presidente es de mínimo 1 caracter.',
            'osc_pres.max'           => 'El nombre del presidente es de máximo 80 caracteres.',
            'osc_replegal.required'  => 'El nombre del representante es obligatorio.',
            'osc_replegal.min'       => 'El nombre del representante es de mínimo 1 caracter.',
            'osc_replegal.max'       => 'El nombre del representante es de máximo 150 caracteres.',
            'osc_srio.required'      => 'El nombre del secretario es obligatorio.',
            'osc_srio.min'           => 'El nombre del secretario es de mínimo 1 caracter.',
            'osc_srio.max'           => 'El nombre del secretario es de máximo 80 caracteres.',
            'osc_tesorero.required'  => 'El nombre del tesorero es obligatorio.',
            'osc_tesorero.min'       => 'El nombre del tesorero es de mínimo 1 caracter.',
            'osc_tesorero.max'       => 'El nombre del tesorero es de máximo 80 caracteres.',

            'osc_objsoc_1.required'    => 'Objeto social es obligatorio.',
            'osc_objsoc_1.min'         => 'Objeto social es de mínimo 1 carácter.',
            'osc_objsoc_1.max'         => 'Objeto social es de máximo 4,000 carácteres.'            
            //'osc_status.required'  => 'El estado de la IAPS es obligatorio.'
            //'osc_foto1.required'   => 'La imagen es obligatoria'
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
            //'osc_desc.'    => 'required|min:1|max:100',
            //'osc_calle'    => 'required|min:1|max:100',
            //'osc_num'      => 'required|min:1|max:30',
            'osc_dom1'       => 'required|min:1|max:150',
            //'osc_colonia'  => 'required|min:1|max:100',
            'entidadfederativa_id' => 'required',
            'municipio_id'   => 'required',            
            'rubro_id'       => 'required',
            'osc_regcons'    => 'required|min:1|max:50',
            //'osc_rfc'      => 'required|min:18|max:18',
            //'osc_cp'       => 'required|numeric|min:5|min:5',            
            //'osc_feccons'  => 'required',
            'anio_id'        => 'required',            
            //'inm_id'       => 'required',
            
            'osc_telefono'   => 'required|min:1|max:60',
            'osc_email'      => 'required|email|min:1|max:60',
            'osc_pres'       => 'required|min:1|max:80',
            'osc_replegal'   => 'required|min:1|max:80',
            'osc_srio'       => 'required|min:1|max:80',
            'osc_tesorero'   => 'required|min:1|max:80',
            'osc_objsoc_1'   => 'required|min:1|max:4000'
            //'accion'       => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'       => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc'   => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
