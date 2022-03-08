<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class informelabRequest extends FormRequest
{
    public function messages()
    {
        return [
            //'iap_id.required'       => 'IAP es obligatorio.',            
            //'periodo_id1.required'  => 'Año de elaboración es obligatorio.',
            //'mes_id1.required'      => 'Mes de elaboración es obligatorio.',
            //'dia_id1.required'      => 'Dia de elaboración es obligatorio.',
            //'programa_desc.min'     => 'Programa es de mínimo 1 caracter.',
            //'programa_desc.max'     => 'Programa es de máximo 500 caracteres.',
            //'programa_desc.required'=> 'Programa es obligatorio.',
            //'actividad_desc.min'    => 'Acitividad es de mínimo 1 caracter.',
            //'actividad_desc.max'    => 'Acitividad es de máximo 500 caracteres.',
            //'actividad_desc.required'=> 'Acitividad es obligatoria.',
            //'objetivo_desc.min'     => 'Objetivo es de mínimo 1 caracter.',
            //'objetivo_desc.max'     => 'Objetivo es de máximo 500 caracteres.',
            //'objetivo_desc.required'=> 'Objetivo es obligatoria.',
            //'umedida_id.required'   => 'Unidad de medida es obligatoria.',
            //'iap_colonia.min'       => 'La colonia es de mínimo 1 caracter.',            
            'mesc_01.required'        => 'Avance del mes de enero es obligatoria.',
            'mesc_01.numeric'         => 'Avance del mes de enero debé ser numerica.',
            'mesc_02.required'        => 'Avance del mes de febrero es obligatoria.',
            'mesc_02.numeric'         => 'Avance del mes de febrero debé ser numerica.',
            'mesc_03.required'        => 'Avance del mes de marzo es obligatoria.',
            'mesc_03.numeric'         => 'Avance del mes de marzo debé ser numerica.',
            'mesc_04.required'        => 'Avance del mes de abril es obligatoria.',
            'mesc_04.numeric'         => 'Avance del mes de abril debé ser numerica.',
            'mesc_05.required'        => 'Avance del mes de mayo es obligatoria.',
            'mesc_05.numeric'         => 'Avance del mes de mayo debé ser numerica.',
            'mesc_06.required'        => 'Avance del mes de junio es obligatoria.',
            'mesc_06.numeric'         => 'Avance del mes de junio debé ser numerica.',
            'mesc_07.required'        => 'Avance del mes de julio es obligatoria.',
            'mesc_07.numeric'         => 'Avance del mes de julio debé ser numerica.',
            'mesc_08.required'        => 'Avance del mes de agosto es obligatoria.',
            'mesc_09.numeric'         => 'Avance del mes de agosto debé ser numerica.',
            'mesc_09.required'        => 'Avance del mes de septiembre es obligatoria.',
            'mesc_09.numeric'         => 'Avance del mes de septiembre debé ser numerica.',
            'mesc_10.required'        => 'Avance del mes de octubre es obligatoria.',
            'mesc_10.numeric'         => 'Avance del mes de octubre debé ser numerica.',
            'mesc_11.required'        => 'Avance del mes de noviembre es obligatoria.',
            'mesc_11.numeric'         => 'Avance del mes de noviembre debé ser numerica.',
            'mesc_12.required'        => 'Avance del mes de diciembre es obligatoria.',
            'mesc_12.numeric'         => 'Avance del mes de diciembre debé ser numerica.'
            //'iap_status.required'   => 'El estado de la IAPS es obligatorio.'
            //'iap_foto1.required'    => 'La imagen es obligatoria'
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
            //'periodo_id1'    => 'required',
            //'mes_id1'        => 'required',
            //'dia_id1'        => 'required',
            //'programa_desc'  => 'required|min:1|max:500',
            //'actividad_desc' => 'required|min:1|max:500',
            //'objetivo_desc'  => 'required|min:1|max:500',
            //'umedida_id'     => 'required',
            'mesc_01'          => 'required',            
            'mesc_02'          => 'required',
            'mesc_03'          => 'required',
            'mesc_04'          => 'required',
            'mesc_05'          => 'required',            
            'mesc_06'          => 'required',
            'mesc_07'          => 'required',            
            'mesc_08'          => 'required',
            'mesc_09'          => 'required',
            'mesc_10'          => 'required',
            'mesc_11'          => 'required',
            'mesc_12'          => 'required'
            //'accion'         => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'         => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc'     => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
