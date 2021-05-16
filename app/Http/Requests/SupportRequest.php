<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupportRequest extends FormRequest
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
            'first_name' => 'required|string|min:2|max:32',
            'last_name' => 'required|string|min:2|max:32',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'issue' => 'required|string|max:127',
            'message' => 'required|string|min:10|max:4096',
        ];
    }
}
