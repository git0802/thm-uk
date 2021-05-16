<?php

namespace App\Http\Requests;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialLink extends FormRequest
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
            'uid' => [
                'required',
            ],
            'name' => ['required', 'max:20'],
            'url'  => ['required', 'url']
        ];
    }
}
