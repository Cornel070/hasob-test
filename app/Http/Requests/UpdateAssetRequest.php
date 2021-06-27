<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAssetRequest extends FormRequest
{
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
            'type' => 'string',
            'description' => 'string',
            'fixed_movable' => 'string',
            'picture' => 'mimes:jpeg,jpg,png,gif',
            'purchase_date' => 'date',
            'start_use_date' => 'date',
            'purchase_price' => 'integer',
            'warranty_expiry_date' => 'date',
            'degredation_in_years' => 'integer',
            'current_value_in_naira' => 'integer',
            'location' => 'string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'res_type'=>'validation_error',
            'errors' => $validator->errors()
        ], 422));
    }
}
