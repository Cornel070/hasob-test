<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AssetAssignmentRequestUpdateRequest extends FormRequest
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
            'asset_id' => 'integer',
            'assignment_date' => 'date',
            'status' => 'string',
            'is_due' => 'string',
            'due_date' => 'date',
            'assigned_user_id' => 'integer',
            'assigned_by' => 'string'
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
