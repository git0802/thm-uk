<?php

namespace App\Http\Requests\Dish;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Helpers\UserHelper;

class CreateDish extends FormRequest
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
            'name' => 'required|string|min:2|max:128',
            'store_id' => 'required|exists:stores,id',
            'product_ids' => 'required|array',
            'image' => 'nullable|image|max:3075',
        ];
    }
}
