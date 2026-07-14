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
            'file' => [
                'required',
                'file',
                'max:5120',
                'mimes:' . implode(',', self::ALLOWED_EXTENSIONS),
            ],
            'folder_id' => ['nullable', 'exists:folders,id'],
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Please select a file to upload.',
            'file.max' => 'File size must not exceed 5MB.',
            'file.mimes' => 'This file type is not allowed. Allowed: ' . implode(', ', self::ALLOWED_EXTENSIONS) . '.',
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
