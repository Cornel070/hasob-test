<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AssetCreateRequest extends FormRequest
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
            'type' => 'required|string',
            'serial_number' => 'required',
            'description' => 'required|string',
            'fixed_movable' => 'required|string',
            'picture' => 'required|mimes:jpeg,jpg,png,gif',
            'purchase_date' => 'required|date',
            'start_use_date' => 'required|date',
            'purchase_price' => 'required|integer',
            'warranty_expiry_date' => 'required|date',
            'degredation_in_years' => 'required|integer',
            'current_value_in_naira' => 'required|integer',
            'location' => 'required|string',
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
