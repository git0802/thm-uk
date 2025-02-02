<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class AllPosts extends FormRequest
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
            'offset' => 'nullable|numeric|min:0|max:9990',
        ];
    }
}
