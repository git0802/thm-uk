<?php

namespace App\Http\Requests;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class ChangeMetrics extends FormRequest
{
    use UserHelper;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->activated();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'weight' => 'required|numeric|min:60|max:600',
            'height' => 'required|numeric|min:50|max:255',
            'age' => 'required|numeric|min:12|max:120',
            'gender' => 'required|in:male,female',
        ];
    }
}
