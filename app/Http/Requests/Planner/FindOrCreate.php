<?php

namespace App\Http\Requests\Planner;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\UserHelper;

class FindOrCreate extends FormRequest
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
            'date' => 'nullable|date_format:"Y-m-d H:i:s"'
        ];
    }
}
