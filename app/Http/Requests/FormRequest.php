<?php


namespace App\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    public function getFormData(): array
    {
        $data = $this->request->all();
        $data = Arr::except($data, [
            '_token',
            '_method',
        ]);

        return $data;
    }

}
