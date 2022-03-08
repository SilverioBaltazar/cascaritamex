<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class visitaquestionRequest extends FormRequest
{
    public function messages()
    {
        return [
            //'iap_id.required'         => 'La IAP es obligatoria.',
            'visitaq_spub1.min'       => 'Nombre de quien atiende diligencia debe ser de mínimo 1 caracter.',
            'visitaq_spub1.max'       => 'Nombre de quien atiende diligencia debe ser de máximo 80 caracteres.',
            'visitaq_spub1.required'  => 'Nombre de quien atiende diligencia es obligatorio.',
            'visitaq_cargo1.min'      => 'Cargo de quien atiende diligencia debe ser de mínimo 1 caracter.',
            'visitaq_cargo1.max'      => 'Cargo de quien atiende diligencia debe ser de máximo 80 caracteres.',
            'visitaq_cargo1.required' => 'Cargo de quien atiende diligencia es obligatorio.',
        
            'preg_id1.required'       => 'La pregunta es necesaria para la evaluación.',
            'preg_id2.required'       => 'La pregunta es necesaria para la evaluación.',
            'preg_id3.required'       => 'La pregunta es necesaria para la evaluación.',
            'preg_id4.required'       => 'La pregunta es necesaria para la evaluación.',
            'preg_id5.required'       => 'La pregunta es necesaria para la evaluación.',
            'preg_id6.required'       => 'La pregunta es necesaria para la evaluación.',
            'preg_id7.required'       => 'La pregunta es necesaria para la evaluación.',
            'preg_id8.required'       => 'La pregunta es necesaria para la evaluación.',
            'preg_id9.required'       => 'La pregunta es necesaria para la evaluación.',
            'preg_id10.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id11.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id12.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id13.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id14.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id15.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id16.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id17.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id18.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id19.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id20.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id21.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id22.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id23.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id24.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id25.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id26.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id27.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id28.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id29.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id30.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id31.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id32.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id33.required'      => 'La pregunta es necesaria para la evaluación.',

            'preg_id34.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id35.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id36.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id37.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id38.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id39.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id40.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id41.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id42.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id43.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id44.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id45.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id46.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id47.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id48.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id49.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id50.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id51.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id52.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id53.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id54.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id55.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id56.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id57.required'      => 'La pregunta es necesaria para la evaluación.',
            'preg_id58.required'      => 'La pregunta es necesaria para la evaluación.'            
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
            //'iap_id' => 'required',
            'visitaq_spub1'  => 'min:1|max:80|required',
            'visitaq_cargo1' => 'min:1|max:80|required',
            //'unidad'     => 'required',
            'preg_id1'  => 'required',
            'preg_id2'  => 'required',
            'preg_id3'  => 'required',
            'preg_id4'  => 'required',
            'preg_id5'  => 'required',
            'preg_id6'  => 'required',
            'preg_id7'  => 'required',
            'preg_id8'  => 'required',
            'preg_id9'  => 'required',
            'preg_id10' => 'required',
            'preg_id11' => 'required',
            'preg_id12' => 'required',
            'preg_id13' => 'required',
            'preg_id14' => 'required',
            'preg_id15' => 'required',
            'preg_id16' => 'required',
            'preg_id17' => 'required',
            'preg_id18' => 'required',
            'preg_id19' => 'required',
            'preg_id20' => 'required',
            'preg_id21' => 'required',
            'preg_id22' => 'required',
            'preg_id23' => 'required',
            'preg_id24' => 'required',
            'preg_id25' => 'required',
            'preg_id26' => 'required',
            'preg_id27' => 'required',
            'preg_id28' => 'required',
            'preg_id29' => 'required',
            'preg_id30' => 'required',
            'preg_id31' => 'required',
            'preg_id32' => 'required',
            'preg_id33' => 'required',

            'preg_id34' => 'required',
            'preg_id35' => 'required',
            'preg_id36' => 'required',
            'preg_id37' => 'required',
            'preg_id38' => 'required',
            'preg_id39' => 'required',
            'preg_id38' => 'required',
            'preg_id39' => 'required',
            'preg_id40' => 'required',
            'preg_id41' => 'required',
            'preg_id42' => 'required',
            'preg_id43' => 'required',
            'preg_id44' => 'required',
            'preg_id45' => 'required',
            'preg_id46' => 'required',
            'preg_id47' => 'required',
            'preg_id48' => 'required',
            'preg_id49' => 'required',
            'preg_id50' => 'required',
            'preg_id51' => 'required',
            'preg_id52' => 'required',
            'preg_id53' => 'required',
            'preg_id54' => 'required',
            'preg_id55' => 'required',
            'preg_id56' => 'required',
            'preg_id57' => 'required',
            'preg_id58' => 'required'            
        ];
    }
}
