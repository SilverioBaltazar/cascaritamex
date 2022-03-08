<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class reqjuridico12Request extends FormRequest
{
    public function messages()
    { 
        return [
            'osc_id.required'  => 'Id de la OSC es obligatorio.',
            'osc_d12.required' => 'El archivo digital es obligatorio'
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
            //'osc_desc.'  => 'required|min:1|max:100',
            'osc_id'       => 'required',
            'osc_d12'      => 'sometimes|mimetypes:application/pdf|max:1500'
        ];
    }
}
