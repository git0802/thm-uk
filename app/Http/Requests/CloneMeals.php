<?php

namespace App\Http\Requests;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class CloneMeals extends FormRequest
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
            'case'     => 'required|in:1,2',
            'meal_ids' => 'required|array',
            'eating'   => 'required_if:case,1|in:breakfast,lunch,dinner,snacks',
            'date'     => 'required|date_format:"Y-m-d"'
        ];
    }
}
