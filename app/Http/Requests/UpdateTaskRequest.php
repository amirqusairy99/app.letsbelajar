<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', 'in:low,medium,high'],
            'status' => ['required', 'in:todo,doing,completed'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Task title is required.',
            'priority.in' => 'Invalid priority selected.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}
