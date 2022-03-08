<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class categoriaRequest extends FormRequest
{
    public function messages()
    {
        return [ 
            'cate_id.required'   => 'La clave del categoria social es obligatoria.',
            'cate_desc.required' => 'El nombre del categoria social es obligatorio.',
            'cate_desc.min'      => 'El nombre del categoria social es de mínimo 1 caracter.',
            'cate_desc.max'      => 'El nombre del categoria social es de máximo 80 caracteres.',
            'cate_desc.regex'    => 'El nombre del categoria social contiene caracteres inválidos.'
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
            'cate_desc' =>  'min:1|max:80|required'
            //'categoria_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
