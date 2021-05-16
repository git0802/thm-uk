<?php

namespace App\Http\Requests;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class ChangePassword extends FormRequest
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
            'old_password' => 'required|string|min:8|max:32',
            'new_password' => 'required|string|min:8|max:32',
            'new_password_confirm' => 'required|same:new_password',
        ];
    }
}
