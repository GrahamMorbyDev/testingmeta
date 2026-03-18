<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        // Adjust authorization as needed (e.g. policies / gates)
        return true;
    }

    public function rules()
    {
        return [
            'agent_id' => ['required', 'integer', 'exists:agents,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', 'in:low,medium,high'],
            'status' => ['required', 'in:open,in_progress,completed'],
        ];
    }
}
