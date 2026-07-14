<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'lecturer_name' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,archived'],
        ];
    }

    public function messages()
    {
        return [
            'subject.required' => 'Subject is required.',
            'name.required' => 'Assignment name is required.',
            'due_date.date' => 'Please enter a valid date.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}
