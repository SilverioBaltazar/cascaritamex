<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class filtro01Request extends FormRequest
{
    public function messages()
    {
        return [
            'periodo_id.required'      => 'El periodo fiscal es obligatorio.', 
            'mes_id.required'          => 'El mes es obligatorio.',
            'visita_tipo1.required'    => 'Seleccionar el tipo, jurídica, asistencial o contable es obligatorio.'
            //'visita_tipo2.required'    => 'Seleccionar el formato del reporte de salida, Excel o PDF.'
            //'visita_contacto.required' => 'El contacto de la IAP es obligatorio.',
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
            'periodo_id'     => 'required', 
            'mes_id'         => 'required',
            'visita_tipo1'   => 'required'
            //'visita_tipo2'   => 'required'
        ];
    }
}
