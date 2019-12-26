<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EgresoCajaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required'
            ],
            'establishment_id' => [
                'required',
            ],
            'monto' => [
                'required'
            ],
            'observacion' => [
                'required'
            ],
            'date_of_issue' => [
                'required',
            ],
            
        ];
    }
}