<?php

namespace App\Http\Requests\Meal;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class AddToPlanner extends FormRequest
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
            'eating'   => 'required|in:breakfast,lunch,dinner,snacks',
            'servings' => 'required|numeric|min:1|max:127',
            'date'     => 'nullable|date_format:"Y-m-d"'

        ];
    }
}
