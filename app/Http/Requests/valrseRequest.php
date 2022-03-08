<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class valrseRequest extends FormRequest
{
    public function messages() 
    {
        return [
            'osc_id.required'      => 'Id de la OSC es obligatorio.',
            'periodo_id.required'  => 'El periodo fiscal es obligatorio.'
            //'osc_d01.required'   => 'El archivo del padron de beneficiarios es obligatorio deberá ser en formato Excel.',
            //'per01_id.requered'  => 'La periodicidad del padron de beneficiarios es obligatorio.',            
            //'num01_id.required'  => 'La frecuencia del padron de beneficiarios es obligatorio.',
            //'osc_edo01.required' => 'El estado de la entrega del padron es obligatorio',
            //'osc_d02.required'   => 'El archivo de listado de personal es obligatorio deberá ser en formato Excel.',
            //'per02_id.requered'  => 'La periodicidad del listado de personal es obligatorio.',            
            //'num02_id.required'  => 'La frecuencia del listado de personal es obligatorio.',
            //'osc_edo02.required' => 'El estado de la entrega del listado de personal es obligatorio',
            //'osc_d03.required'   => 'El archivo de Detección de necesidades es obligatorio deberá ser en formato Excel.',
            //'per03_id.requered'  => 'La periodicidad de Detección de necesidades es obligatorio.',            
            //'num03_id.required'  => 'La frecuencia del listado de Detección de necesidades es obligatorio.',
            //'osc_edo03.required' => 'El estado de la entrega del Detección de necesidades es obligatorio',
            //'osc_d04.required'   => 'El archivo de Inventario de activo fijo es obligatorio deberá ser en formato Excel.',
            //'per04_id.requered'  => 'La periodicidad del Inventario de activo fijo es obligatorio.',            
            //'num04_id.required'  => 'La frecuencia del Inventario de activo fijo es obligatorio.',
            //'osc_edo04.required' => 'El estado de la entrega del Inventario de activo fijo es obligatorio',
            //'osc_d05.required'   => 'El archivo de Presupuesto anual es obligatorio deberá ser en formato Excel.',
            //'per05_id.requered'  => 'La periodicidad del Presupuesto anual es obligatorio.',            
            //'num05_id.required'  => 'La frecuencia del Presupuesto anual es obligatorio.',
            //'osc_edo05.required' => 'El estado de la entrega del Presupuesto anual es obligatorio',
            //'osc_d06.required'   => 'El archivo de Programa de trabajo es obligatorio deberá ser en formato Excel.',
            //'per06_id.requered'  => 'La periodicidad del Programa de trabajo es obligatorio.',            
            //'num06_id.required'  => 'La frecuencia del Programa de trabajo es obligatorio.',
            //'osc_edo06.required' => 'El estado de la entrega del Programa de trabajo es obligatorio',
            //'osc_d07.required'   => 'El archivo de Constancia de autorización de donaciones es obligatorio deberá ser en formato PDF.',
            //'per07_id.requered'  => 'La periodicidad del Constancia de autorización de donaciones es obligatorio.',            
            //'num07_id.required'  => 'La frecuencia del Constancia de autorización de donaciones es obligatorio.',
            //'osc_edo07.required' => 'El estado de la entrega del Constancia de autorización de donaciones es obligatorio',
            //'osc_d08.required'   => 'El archivo de la Constancia de cumplimiento de declaración ante el SAT es obligatorio deberá ser en formato Excel.',
            //'per08_id.requered'  => 'La periodicidad de la Constancia de cumplimiento de declaración en el SAT es obligatorio.',
            //'num08_id.required'  => 'La frecuencia de la Constancia de cumplimiento de declaración en el SAT es obligatorio.',
            //'osc_edo08.required' => 'El estado de la entrega de la Constancia de cumplimiento de declaración en el SAT es obligatorio' 
            //'osc_foto1.required' => 'La imagen es obligatoria'
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
            //'osc_d01'   => 'required',
            //'osc_d01'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per01_id'  => 'required',            
            //'num01_id'  => 'required',
            //'osc_edo01' => 'required',
            //'osc_d02'   => 'required',
            //'osc_d02'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per02_id'  => 'required',            
            //'num02_id'  => 'required',
            //'osc_edo02' => 'required',
            //'osc_d03'   => 'required',
            //'osc_d03'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per03_id'  => 'required',            
            //'num03_id'  => 'required',
            //'osc_edo03' => 'required',
            //'osc_d04'   => 'required',
            //'osc_d04'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per04_id'  => 'required',            
            //'num04_id'  => 'required',
            //'osc_edo04' => 'required',
            //'osc_d05'   => 'required',
            //'osc_d05'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per05_id'  => 'required',            
            //'num05_id'  => 'required',
            //'osc_edo05' => 'required',
            //'osc_d06'   => 'required',
            //'osc_d06'   => 'sometimes|mimes:xls,xlsx|max:2048',
            //'per06_id'  => 'required',            
            //'num06_id'  => 'required',
            //'osc_edo06' => 'required',
            //'osc_d07'   => 'required',
            //'osc_d07' => 'sometimes|mimetypes:application/pdf|max:2048',
            //'osc_d07' => 'sometimes|mimetypes:pdf|max:2048',
            //'osc_d07' => 'sometimes|mimes:application/pdf|max:2048',
            //'osc_d07' => 'sometimes|mimes:pdf|max:2048',
            //'per07_id'  => 'required',            
            //'num07_id'  => 'required',
            //'osc_edo07' => 'required',
            //'osc_d08'   => 'required',
            //'osc_d08' => 'sometimes|mimetypes:application/pdf|max:2048',
            //'osc_d08' => 'sometimes|mimetypes:pdf|max:2048',
            //'osc_d08' => 'sometimes|mimes:application/pdf|max:2048',
            //'osc_d08' => 'sometimes|mimes:pdf|max:2048'             
            //'per08_id'  => 'required',
            //'num08_id'  => 'required',
            //'osc_edo08' => 'required'
            //'apor_recibe'  => 'required|min:1|max:100'
            //'accion'        => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'        => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
