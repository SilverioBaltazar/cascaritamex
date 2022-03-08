<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class reqoperativos1Request extends FormRequest
{
    public function messages()
    {
        return [
            'osc_id.required'      => 'Id de la OSC es obligatorio.',
            'periodo_id.required'  => 'El periodo fiscal es obligatorio.',
            'osc_d1'               => 'El archivo digital en formato PDF es obligatorio.'
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
            'periodo_id'  => 'required',
            'osc_d1'      => 'sometimes|mimetypes:application/pdf|max:1500'   
        ];
    }
}
