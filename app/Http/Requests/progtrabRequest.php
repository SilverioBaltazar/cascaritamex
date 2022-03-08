<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class progtrabRequest extends FormRequest
{
    public function messages()
    {
        return [
            'periodo_id.required'  => 'Periodo fiscal es obligatorio.',
            'osc_id.required'      => 'OSC es obligatorio.',            
            'periodo_id1.required' => 'Año de elaboración es obligatorio.',
            'mes_id1.required'     => 'Mes de elaboración es obligatorio.',
            'dia_id1.required'     => 'Dia de elaboración es obligatorio.',
            'responsable.min'      => 'Responsable es de mínimo 8 carácteres.',
            'responsable.max'      => 'Responsable es de máximo 80 carácteres.',
            'responsable.required' => 'Responsable es obligatorio.',
            'elaboro.min'          => 'Elaboró es de mínimo 8 carácteres.',
            'elaboro.max'          => 'Elaboró es de máximo 80 carácteres.',
            'elaboro.required'     => 'Elaboró es obligatorio.',
            'autorizo.min'         => 'Autorizo es de mínimo 8 carácteres.',
            'autorizo.max'         => 'Autorizo es de máximo 80 carácteres.',
            'autorizo.required'    => 'Autorizo es obligatorio.'                        
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
            'periodo_id'   => 'required',
            'osc_id'       => 'required',
            'periodo_id1'  => 'required',
            'mes_id1'      => 'required',
            'dia_id1'      => 'required',            
            'responsable'  => 'required',
            'elaboro'      => 'required',
            'autorizo'     => 'required'
            //'accion'     => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'     => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
