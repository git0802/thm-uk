<?php

namespace App\Http\Requests\Planner;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class Goals extends FormRequest
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
            'weight'         => 'required|numeric|min:60|max:600',
            'goal'           => 'required|numeric|min:-2.5|max:2.5',
            'calories_goal'  => 'required|numeric|min:1000|max:5000',
            'need_to_update' => 'sometimes|required|boolean'
        ];
    }
}
