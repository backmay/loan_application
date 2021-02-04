<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAndUpdateRequest extends FormRequest
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
            'loan_amount' => 'required|integer',
            'loan_term' => 'required|integer|between:1,50',
            'interest_rate' => ['required', 'regex:/^([1-9]|3[0-5]|[12]\d{1,2}|36\.00){1}(\.[0-9]{1,2})?$/'],
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2017,2050'
        ];
    }
}
