<?php

namespace App\Http\Requests;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class RenewSubscription extends FormRequest
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
            'number' => 'required|digits:16',
            'exp_year' => 'required|numeric|min:' . date('y') . '|max:99',
            'exp_month' => 'required|numeric|min:1|max:12',
            'cvc' => 'required|digits:3',
            'coupon' => 'nullable|exists:coupon_codes,code',
        ];
    }
}
