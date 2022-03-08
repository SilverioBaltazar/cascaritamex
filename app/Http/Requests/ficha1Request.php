<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ficha1Request extends FormRequest
{
    public function messages()
    {
        return [
            'eq_arc1.required' => 'El archivo digital de la ficha del equipo en formato PDF es obligatorio'
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
            //'iap_desc.'  => 'required|min:1|max:100',
            'eq_arc1'      => 'sometimes|mimetypes:application/pdf|max:1500'
            //'iap_foto2'  => 'required|image'
            //'accion'     => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'     => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
