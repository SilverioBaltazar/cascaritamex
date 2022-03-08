<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class personalRequest extends FormRequest
{
    public function messages()
    {
        return [
            //'iap_id.required'           => 'La IAP es obligatorio.',
            'primer_apellido.required'  => 'Apellido paterno es obligatorio.',
            'primer_apellido.min'       => 'Apellido paterno es de mínimo 1 carácteres.',
            'primer_apellido.max'       => 'Apellido paterno es de máximo 80 carácteres.',
            //'segundo_apellido.required' => 'Apellido materno es obligatorio.',
            //'segundo_apellido.min'      => 'Apellido materno es de mínimo 1 carácteres.',
            //'segundo_apellido.max'      => 'Apellido materno es de máximo 80 carácteres.',
            'nombres.required'          => 'Nombre(s) es obligatorio.',
            'nombres.min'               => 'Nombre(s) es de mínimo 1 carácteres.',
            'nombres.max'               => 'Nombre(s) es de máximo 80 carácteres.',     
            'curp.required'             => 'CURP es obligatorio.',
            'curp.min'                  => 'CURP es de mínimo 18 carácteres.',
            'curp.max'                  => 'CURP es de máximo 18 carácteres.',                       
            //'entidad_fed_id.required'   => 'Entidad federativa es obligatoria.',
            //'municipio_id.required'     => 'Municipio es obligatorio.',            
            //'cp.required'             => 'Código postal es obligatorio.',
            //'cp.min'                  => 'Código postal es de mínimo 5 caracteres.',
            //'cp.max'                  => 'Código postal es de máximo 5 caracteres.',
            //'cp.numeric'              => 'Código postal debe ser numerico.',            
            'telefono.required'         => 'Teléfono es obligatorio y digitar soló numeros preferentemente.',
            'telefono.min'              => 'Teléfono es de mínimo 1 caracteres númericos preferentemente.',
            'telefono.max'              => 'Teléfono es de máximo 30 caracteres numéricos prefentemente.',
            //'fecha_ingreso.required'    => 'Fecha de ingreso es obligatoria dd/mm/aaaa.',
            'puesto.required'           => 'Puesto es obligatorio.',            
            'grado_estudios_id.required'=> 'Grado de estudios es obligatorio.',
            'tipoemp_id.required'       => 'Tipo de empleado es obligatorio.',
            'claseemp_id.required'      => 'Clase de empleado es obligatorio.',
            'sueldo_mensual.required'   => 'Sueldo mensual es obligatorio.',
            'periodo_id1.required'      => 'Año de nacimiento es obligatorio.',
            'mes_id1.required'          => 'Mes de nacimiento es obligatorio.',
            'dia_id1.required'          => 'Día de nacimiento es obligatorio.',
            'periodo_id2.required'      => 'Año de ingreso es obligatorio.',
            'mes_id2.required'          => 'Mes de ingreso es obligatorio.',
            'dia_id2.required'          => 'Día de ingreso es obligatorio.'
            //'servicios_brindan.required'=> 'Servicios que le brinda la IAP es obligatorio.'
            //'iap_foto1.required' => 'La imagen es obligatoria'
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
            //'iap_id'            => 'required',
            'primer_apellido'   => 'required|min:1|max:80',
            //'segundo_apellido'  => 'required|min:1|max:80',
            'nombres'           => 'required|min:1|max:80',
            'curp'              => 'required|min:1|max:18',
            //'entidad_fed_id'    => 'required',
            //'municipio_id'      => 'required',            
            'telefono'          => 'required|min:1|max:30',
            'puesto'            => 'required|min:1|max:150',
            'grado_estudios_id' => 'required',
            'tipoemp_id'        => 'required',
            'claseemp_id'       => 'required',
            'sueldo_mensual'    => 'required',
            'periodo_id1'       => 'required',
            'mes_id1'           => 'required',
            'dia_id1'           => 'required',
            'periodo_id2'       => 'required',
            'mes_id2'           => 'required',            
            'dia_id2'           => 'required'
            //'servicios_brindan' => 'required'
            //'iap_foto1'    => 'required|image',
            //'iap_foto2'    => 'required|image'
            //'accion'        => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'        => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
