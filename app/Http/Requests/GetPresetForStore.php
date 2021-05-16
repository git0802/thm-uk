<?php

namespace App\Http\Requests;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetPresetForStore extends FormRequest
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
            'store_id' => [
                'required',
                'integer',
                Rule::exists('stores', 'id')->whereNull('owner_id')
            ]
        ];
    }

    public function messages()
    {
       return [
           'store_id.exists' => __('preset.404')
       ];
    }
}
