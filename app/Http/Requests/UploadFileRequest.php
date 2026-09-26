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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('files')) {
                $user = $this->user();
                $storageLimitBytes = $user->storage_limit ?? (200 * 1024 * 1024);
                $storageUsedBytes = (float) \App\Models\File::where('uploaded_by', $user->id)->sum('size');
                
                $uploadingBytes = 0;
                foreach ($this->file('files') as $file) {
                    if ($file && $file->isValid()) {
                        $uploadingBytes += $file->getSize();
                    }
                }
                
                if (($storageUsedBytes + $uploadingBytes) > $storageLimitBytes) {
                    $validator->errors()->add('files', 'This upload would exceed your ' . round($storageLimitBytes / (1024 * 1024)) . ' MB storage limit.');
                }
            }
        });
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
