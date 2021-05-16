<?php

namespace App\Http\Requests\Store;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStore extends FormRequest
{
    use UserHelper;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:128|unique:stores,name,' . $this->store->id,
            'image' => 'nullable|image|max:3075',
        ];
    }
}
