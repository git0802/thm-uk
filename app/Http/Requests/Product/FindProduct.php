<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class FindProduct extends FormRequest
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
            'search' => 'required|string|min:2|max:255',
            'store_id' => 'required|numeric|exists:stores,id',
            'without_dish' => 'sometimes|in:true,false'
        ];
    }
}
