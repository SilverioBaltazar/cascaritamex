<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class progdtrabRequest extends FormRequest
{
    public function messages()
    {
        return [
            //'iap_id.required'         => 'IAP es obligatorio.',            
            //'periodo_id1.required'    => 'Año de elaboración es obligatorio.',
            //'mes_id1.required'        => 'Mes de elaboración es obligatorio.',
            //'dia_id1.required'        => 'Dia de elaboración es obligatorio.',
            'programa_desc.min'       => 'Programa es de mínimo 1 caracter.',
            'programa_desc.max'       => 'Programa es de máximo 500 caracteres.',
            'programa_desc.required'  => 'Programa es obligatorio.',
            'actividad_desc.min'      => 'Acitividad es de mínimo 1 caracter.',
            'actividad_desc.max'      => 'Acitividad es de máximo 500 caracteres.',
            'actividad_desc.required' => 'Acitividad es obligatoria.',
            'objetivo_desc.min'       => 'Objetivo es de mínimo 1 caracter.',
            'objetivo_desc.max'       => 'Objetivo es de máximo 500 caracteres.',
            'objetivo_desc.required'  => 'Objetivo es obligatoria.',
            'umedida_id.required'     => 'Unidad de medida es obligatoria.',
            //'iap_colonia.min'       => 'La colonia es de mínimo 1 caracter.',            
            'mesp_01.required'        => 'Meta programada del mes de enero es obligatoria.',
            'mesp_01.numeric'         => 'Meta programada del mes de enero debé ser numerica.',
            'mesp_02.required'        => 'Meta programada del mes de febrero es obligatoria.',
            'mesp_02.numeric'         => 'Meta programada del mes de febrero debé ser numerica.',
            'mesp_03.required'        => 'Meta programada del mes de marzo es obligatoria.',
            'mesp_03.numeric'         => 'Meta programada del mes de marzo debé ser numerica.',
            'mesp_04.required'        => 'Meta programada del mes de abril es obligatoria.',
            'mesp_04.numeric'         => 'Meta programada del mes de abril debé ser numerica.',
            'mesp_05.required'        => 'Meta programada del mes de mayo es obligatoria.',
            'mesp_05.numeric'         => 'Meta programada del mes de mayo debé ser numerica.',
            'mesp_06.required'        => 'Meta programada del mes de junio es obligatoria.',
            'mesp_06.numeric'         => 'Meta programada del mes de junio debé ser numerica.',
            'mesp_07.required'        => 'Meta programada del mes de julio es obligatoria.',
            'mesp_07.numeric'         => 'Meta programada del mes de julio debé ser numerica.',
            'mesp_08.required'        => 'Meta programada del mes de agosto es obligatoria.',
            'mesp_09.numeric'         => 'Meta programada del mes de agosto debé ser numerica.',
            'mesp_09.required'        => 'Meta programada del mes de septiembre es obligatoria.',
            'mesp_09.numeric'         => 'Meta programada del mes de septiembre debé ser numerica.',
            'mesp_10.required'        => 'Meta programada del mes de octubre es obligatoria.',
            'mesp_10.numeric'         => 'Meta programada del mes de octubre debé ser numerica.',
            'mesp_11.required'        => 'Meta programada del mes de noviembre es obligatoria.',
            'mesp_11.numeric'         => 'Meta programada del mes de noviembre debé ser numerica.',
            'mesp_12.required'        => 'Meta programada del mes de diciembre es obligatoria.',
            'mesp_12.numeric'         => 'Meta programada del mes de diciembre debé ser numerica.'
            //'iap_status.required' => 'El estado de la IAPS es obligatorio.'
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
            //'periodo_id1'      => 'required',
            //'mes_id1'          => 'required',
            //'dia_id1'          => 'required',
            'programa_desc'    => 'required|min:1|max:500',
            'actividad_desc'   => 'required|min:1|max:500',
            'objetivo_desc'    => 'required|min:1|max:500',
            'umedida_id'       => 'required',
            'mesp_01'          => 'required',            
            'mesp_02'          => 'required',
            'mesp_03'          => 'required',
            'mesp_04'          => 'required',
            'mesp_05'          => 'required',            
            'mesp_06'          => 'required',
            'mesp_07'          => 'required',            
            'mesp_08'          => 'required',
            'mesp_09'          => 'required',
            'mesp_10'          => 'required',
            'mesp_11'          => 'required',
            'mesp_12'          => 'required'
            //'accion'        => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'        => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
