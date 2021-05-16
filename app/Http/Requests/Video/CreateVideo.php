<?php

namespace App\Http\Requests\Video;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class CreateVideo extends FormRequest
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
            'title'         => 'required|string|max:191',
            'description'   => 'required|string|max:191',
            'url'           => 'required|url|max:191',
            'image'         => 'nullable|image|min:1|max:3075',

        ];
    }
}
