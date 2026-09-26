<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'files' => ['required', 'array', 'min:1'],
            'files.*' => [
                'file',
                'max:15360',
                'mimes:' . implode(',', self::ALLOWED_EXTENSIONS),
            ],
            'folder_id' => ['nullable', 'exists:folders,id'],
        ];
    }

    public function messages()
    {
        return [
            'files.required' => 'Please select at least one file to upload.',
            'files.*.max' => 'File size must not exceed 15MB.',
            'files.*.mimes' => 'This file type is not allowed. Allowed: ' . implode(', ', self::ALLOWED_EXTENSIONS) . '.',
        ];
    }

    public const ALLOWED_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'pdf',
        'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'odp',
        'txt', 'csv', 'md',
        'zip', 'rar',
    ];
}
