<?php

namespace App\Http\Requests\Preset;

use App\Helpers\UserHelper;
use App\PlannerPreset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddProductToPreset extends FormRequest
{
    use UserHelper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->whereNull('owner_id')
            ],
            'eating' => [
                'required',
                'string',
                'in:breakfast,lunch,dinner,snacks',
            ],
            'day'   => [
                'required',
                'string',
                Rule::in(PlannerPreset::DAYS)
            ],
        ];
    }


    public function messages()
    {
        return [
            'eating.in' => 'Eating is invalid; It must be breakfast, lunch, dinner or snacks',
            'day.in'    => 'Day is invalid. Day must be: ' . implode(',', PlannerPreset::DAYS),
        ];
    }
}
