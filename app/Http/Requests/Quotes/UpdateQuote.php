<?php

namespace App\Http\Requests\Quotes;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class UpdateQuote extends FormRequest
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
            'quote' => 'required|string|max:1024|not_in:quotes'
        ];
    }
}
