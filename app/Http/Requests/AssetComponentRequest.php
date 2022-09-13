<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetComponentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'description' => 'required|max:50',
            'size'    => ['required', 'integer', 'max:1000000000', 'min:1'],
        ];
    }
}
