<?php

namespace App\Http\Requests;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class ChangeName extends FormRequest
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
            'name' => 'required|string|min:2|max:24',
            'last_name' => 'required|string|min:2|max:24',
        ];
    }
}
