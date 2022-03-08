<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class padron1Request extends FormRequest
{
    public function messages()
    {
        return [
            'arc_1.required' => 'Archivo digital de CURP en formato PDF es obligatorio.'
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
            'arc_1'       => 'sometimes|mimetypes:application/pdf|max:1500'
            //'iap_foto2' => 'required|image'
        ];
    }
}
