<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class reqoperativosRequest extends FormRequest
{
    public function messages()
    {
        return [
            'osc_id.required'      => 'Id de la OSC es obligatorio.',
            'periodo_id.required'  => 'El periodo fiscal es obligatorio.'
            //'iap_d01.required'   => 'El archivo del padron de beneficiarios es obligatorio deberá ser en formato Excel.',
            //'per01_id.requered'  => 'La periodicidad del padron de beneficiarios es obligatorio.',            
            //'num01_id.required'  => 'La frecuencia del padron de beneficiarios es obligatorio.',
            //'iap_edo01.required' => 'El estado de la entrega del padron es obligatorio',
            //'iap_d02.required'   => 'El archivo de listado de personal es obligatorio deberá ser en formato Excel.',
            //'per02_id.requered'  => 'La periodicidad del listado de personal es obligatorio.',            
            //'num02_id.required'  => 'La frecuencia del listado de personal es obligatorio.',
            //'iap_edo02.required' => 'El estado de la entrega del listado de personal es obligatorio',
            //'iap_d03.required'   => 'El archivo de Detección de necesidades es obligatorio deberá ser en formato Excel.',
            //'per03_id.requered'  => 'La periodicidad de Detección de necesidades es obligatorio.',            
            //'num03_id.required'  => 'La frecuencia del listado de Detección de necesidades es obligatorio.',
            //'iap_edo03.required' => 'El estado de la entrega del Detección de necesidades es obligatorio',
            //'iap_d04.required'   => 'El archivo de Inventario de activo fijo es obligatorio deberá ser en formato Excel.',
            //'per04_id.requered'  => 'La periodicidad del Inventario de activo fijo es obligatorio.',            
            //'num04_id.required'  => 'La frecuencia del Inventario de activo fijo es obligatorio.',
            //'iap_edo04.required' => 'El estado de la entrega del Inventario de activo fijo es obligatorio',
            //'iap_d05.required'   => 'El archivo de Presupuesto anual es obligatorio deberá ser en formato Excel.',
            //'per05_id.requered'  => 'La periodicidad del Presupuesto anual es obligatorio.',            
            //'num05_id.required'  => 'La frecuencia del Presupuesto anual es obligatorio.',
            //'iap_edo05.required' => 'El estado de la entrega del Presupuesto anual es obligatorio',
            //'iap_d06.required'   => 'El archivo de Programa de trabajo es obligatorio deberá ser en formato Excel.',
            //'per06_id.requered'  => 'La periodicidad del Programa de trabajo es obligatorio.',            
            //'num06_id.required'  => 'La frecuencia del Programa de trabajo es obligatorio.',
            //'iap_edo06.required' => 'El estado de la entrega del Programa de trabajo es obligatorio',
            //'iap_d07.required'   => 'El archivo de Constancia de autorización de donaciones es obligatorio deberá ser en formato PDF.',
            //'per07_id.requered'  => 'La periodicidad del Constancia de autorización de donaciones es obligatorio.',            
            //'num07_id.required'  => 'La frecuencia del Constancia de autorización de donaciones es obligatorio.',
            //'iap_edo07.required' => 'El estado de la entrega del Constancia de autorización de donaciones es obligatorio',
            //'iap_d08.required'   => 'El archivo de la Constancia de cumplimiento de declaración ante el SAT es obligatorio deberá ser en formato Excel.',
            //'per08_id.requered'  => 'La periodicidad de la Constancia de cumplimiento de declaración en el SAT es obligatorio.',
            //'num08_id.required'  => 'La frecuencia de la Constancia de cumplimiento de declaración en el SAT es obligatorio.',
            //'iap_edo08.required' => 'El estado de la entrega de la Constancia de cumplimiento de declaración en el SAT es obligatorio' 
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
            'osc_id'      => 'required',
            'periodo_id'  => 'required'        
            //'iap_desc.' => 'required|min:1|max:100',
            //'iap_id'    => 'required',
            //'periodo_id'=> 'required',
            //'iap_d01'   => 'required',
            //'iap_d01'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per01_id'  => 'required',            
            //'num01_id'  => 'required',
            //'iap_edo01' => 'required',
            //'iap_d02'   => 'required',
            //'iap_d02'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per02_id'  => 'required',            
            //'num02_id'  => 'required',
            //'iap_edo02' => 'required',
            //'iap_d03'   => 'required',
            //'iap_d03'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per03_id'  => 'required',            
            //'num03_id'  => 'required',
            //'iap_edo03' => 'required',
            //'iap_d04'   => 'required',
            //'iap_d04'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per04_id'  => 'required',            
            //'num04_id'  => 'required',
            //'iap_edo04' => 'required',
            //'iap_d05'   => 'required',
            //'iap_d05'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per05_id'  => 'required',            
            //'num05_id'  => 'required',
            //'iap_edo05' => 'required',
            //'iap_d06'   => 'required',
            //'iap_d06'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per06_id'  => 'required',            
            //'num06_id'  => 'required',
            //'iap_edo06' => 'required',
            //'iap_d07'   => 'required',
            //'iap_d07' => 'sometimes|mimetypes:application/pdf|max:2048',
            //'iap_d07' => 'sometimes|mimetypes:pdf|max:2048',
            //'iap_d07' => 'sometimes|mimes:application/pdf|max:2048',
            //'iap_d07' => 'sometimes|mimes:pdf|max:2048',
            //'per07_id'  => 'required',            
            //'num07_id'  => 'required',
            //'iap_edo07' => 'required',
            //'iap_d08'   => 'required',
            //'iap_d08' => 'sometimes|mimetypes:application/pdf|max:2048',
            //'iap_d08' => 'sometimes|mimetypes:pdf|max:2048',
            //'iap_d08' => 'sometimes|mimes:application/pdf|max:2048',
            //'iap_d08' => 'sometimes|mimes:pdf|max:2048'             
            //'per08_id'  => 'required',
            //'num08_id'  => 'required',
            //'iap_edo08' => 'required'
            //'apor_recibe'  => 'required|min:1|max:100'
            //'accion'        => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'        => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
