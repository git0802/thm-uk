<?php

namespace App\Http\Requests\Meal;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class IsMealEaten extends FormRequest
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
            'is_eaten' => 'required|boolean'
        ];
    }
}
