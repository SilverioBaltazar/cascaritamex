<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class catformatoRequest extends FormRequest
{
    public function messages()
    {
        return [
            //'formato_id.required'   => 'Seleccionar id del curso.',
            'formato_desc.min'         => 'El nombre del tipo de archivo es de mínimo 1 caracteres.',
            'formato_desc.max'         => 'El nombre del tipo de archivo es de máximo 30 caracteres.',
            'formato_desc.required'    => 'El nombre del tipo de archivo es obligatorio.',
            'formato_etiq.min'         => 'La etiqueta es de mínimo 1 caracteres.',
            'formato_etiq.max'         => 'La etiqueta es de máximo 30 caracteres.',
            'formato_etiq.required'    => 'La etiqueta es obligatoria.',
            'formato_comando1.min'     => 'El comando 1 es de mínimo 1 caracteres.',
            'formato_comando1.max'     => 'El comando 1 es de máximo 30 caracteres.',
            'formato_comando1.required'=> 'El comando 1 es obligatorio.'           
            //'curso_status.required' => 'El estado del curso es obligatorio'
            //'trx_desc.regex' => 'El nombre de la función contiene caracteres inválidos.'
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
            'formato_desc'     => 'required|min:1|max:30',
            'formato_etiq'     => 'required|min:1|max:30',
            'formato_comando1' => 'required|min:1|max:30'
            //'curso_status' => 'required'
            //'trx_desc' => 'min:1|max:100|required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i'
        ];
    }
}
