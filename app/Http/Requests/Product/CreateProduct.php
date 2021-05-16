<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class CreateProduct extends FormRequest
{
    use UserHelper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->activated();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'serving_size' => 'required|string|max:255',
            'calories' => 'required|integer',
            'cost' => 'required|numeric',
            'image' => 'nullable|image|max:3075',
            'clone_id' => 'nullable|integer',
            'edit_id' => 'nullable|integer'
        ];
    }
}
