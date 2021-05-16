<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;
use Illuminate\Validation\Rule;

class CreateStore extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('stores', 'name')->where(function ($query) {
                    return $query->where('owner_id', null)->orWhere('owner_id', $this->id());
                }),
                'string',
                'max:191'
            ],
            'image' => 'nullable|image|min:1|max:3075',
            'is_default' => 'nullable|in:true,false'
        ];
    }
}
