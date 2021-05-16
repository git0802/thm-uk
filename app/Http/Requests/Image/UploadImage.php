<?php

namespace App\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class UploadImage extends FormRequest
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
            'upload' => 'required|image'
        ];
    }
}
