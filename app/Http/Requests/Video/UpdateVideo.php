<?php

namespace App\Http\Requests\Video;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVideo extends FormRequest
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
            // i know. but laravel thinks that this attr is string ¯\_(ツ)_/¯
            'del_image'     => 'in:true,false'
        ];
    }
}
