<?php

namespace LaravelLiberu\DataImport\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelLiberu\DataImport\Enums\Types;

class ValidateTemplate extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'template' => 'required|file',
            'type' => 'string|in:'.Types::keys()->implode(','),
        ];
    }
}
