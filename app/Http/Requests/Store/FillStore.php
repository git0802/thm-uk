<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class FillStore extends FormRequest
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
            'csv' => 'required|file|mimes:csv,txt',
            'name' => 'required|string|max:255',
            'serving_size' => 'required|string|max:255',
            'package_size' => 'required|string|max:255',
            'cost' => 'required|string|max:255',
            'calories' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'original_url' => 'required|string|max:255',
        ];
    }
}
