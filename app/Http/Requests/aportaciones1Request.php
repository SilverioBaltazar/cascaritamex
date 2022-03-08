<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class aportaciones1Request extends FormRequest
{
    public function messages()
    {
        return [
            //'iap_desc.required' => 'El nombre de la IAP es obligatorio.',
            //'periodo_id.required' => 'El periodo es obligatoria.',
            //'banco_id.required' => 'El banco es obligatorio.',            
            //'mes_id.requered' => 'El mes es obligatorio.',            
            //'apor_concepto.required' => 'Concepto de la aportación.',
            //'apor_monto.required' => 'Cantidad de la aportacion.',
            //'apor_recibe.min' => 'El nombre de la persona que recibe la aportación monetaria de mínimo 1 caracter.',
            //'apor_recibe.max' => 'El nombre de la persona que recibe la aportación monetaria es de máximo 80 caracteres.',
            //'apor_entrega.min' => 'El nombre de la persona que entrega la aportación monetaria es de mínimo 1 caracter.',
            //'apor_entrega.max' => 'El nombre de la persona que entrega la aportación monetaria es de máximo 80 caracteres.'
            'apor_compdepo.required' => 'Comprobante de ficha de deposito es obligatorio formato PDF'
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
            'apor_compdepo' => 'sometimes|mimetypes:application/pdf|max:2048',
            'apor_compdepo' => 'sometimes|mimetypes:pdf|max:2048',
            'apor_compdepo' => 'sometimes|mimes:application/pdf|max:2048',
            'apor_compdepo' => 'sometimes|mimes:pdf|max:2048'             
        ];
    }
}
