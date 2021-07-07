<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RegisterRequest extends FormRequest
{
    /**
     * Unauthorized users only.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return !Auth::guard('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:24',
            'last_name' => 'required|string|min:2|max:24',
            'email' => 'required|email|unique:users,email',
            'confirm_email' => 'required|same:email',
            'password' => 'required|string|min:6|max:32',
            'confirm_password' => 'required|same:password',
            'phone' => 'required|max:20',
            'weight' => 'required|numeric|min:60|max:600',
            'height' => 'required|numeric|min:50|max:255',
            'age' => 'required|numeric|min:12|max:120',
            'gender' => 'required|in:male,female',
            'initial_goal' => 'required|numeric|min:-2.5|max:2.5',
            'initial_calories_goal' => 'required|numeric|min:1000|max:5000',
        ];
    }
}
