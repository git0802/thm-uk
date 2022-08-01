<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class UpdateGuestSettings extends FormRequest
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
            'settings.enabled' => 'required|boolean',
            'settings.user.name' => 'required|string|min:2|max:24',
            'settings.user.weight' => 'required|numeric|min:60|max:600',
            'settings.user.height' => 'required|numeric|min:50|max:255',
            'settings.user.age' => 'required|numeric|min:12|max:120',
            'settings.user.gender' => 'required|in:male,female',
        ];
    }
}
