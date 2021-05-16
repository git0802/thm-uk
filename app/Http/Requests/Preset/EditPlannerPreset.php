<?php

namespace App\Http\Requests\Preset;

use App\PlannerPreset;
use App\Helpers\UserHelper;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EditPlannerPreset extends FormRequest
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
            'name'            => 'sometimes|required|string|max:191',
            'description'     => 'nullable',
            'preset_case'     => [
                'required',
                Rule::in(PlannerPreset::getCases())],
            'store_id' => [
                'nullable',
                'integer',
                Rule::exists('stores', 'id')->whereNull('owner_id')
            ]];
    }

    public function messages()
    {
        return [
            'related_store_d.exists' => 'This store doesn\'t exists'
        ];
    }
}
