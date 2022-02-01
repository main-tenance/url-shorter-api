<?php

namespace App\Http\Requests\Links;

use App\Http\Requests\FormRequest;

class LinksCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'long_url' => 'required|url|max:255',
        ];
    }
}
