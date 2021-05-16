<?php

namespace App\Http\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class UpdateSet extends FormRequest
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
            'name' => 'required|string|min:2|max:127',
            'type' => 'required|in:percent,month,fixed',
            'value' => 'required|numeric',
            'title' => 'required|string|min:2|max:127'
        ];
    }
}
