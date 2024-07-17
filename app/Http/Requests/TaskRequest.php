<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'icon' => [
                'nullable',
            ],
            'durationOfWork' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }
}
