<?php

namespace App\Http\Requests\Planner;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class ExtraCalories extends FormRequest
{
    use UserHelper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->activated();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:ate,burn',
            'value' => 'required|numeric|min:1|max:5000',
            'day' => 'required|date_format:d-m-Y',
        ];
    }
}
