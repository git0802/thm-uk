<?php

namespace App\Http\Requests;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePresetCSV extends FormRequest
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
            'csv' =>
                'required|file|mimes:csv,txt',
            'product_name' => 'required|string|max:255',
            'day' => 'required|string|max:255',
            'eating' => 'required|string|max:255',
        ];
    }
}
