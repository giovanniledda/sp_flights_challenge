<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlightRequest extends FormRequest
{
    public function rules()
    {
        return [
            'depCode' => 'required|exists:airports,code',
            'arrCode' => 'required|exists:airports,code',
        ];
    }

    public function messages()
    {
        return [
          'depCode:exists' => 'The departure code must belong to a real airport'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
