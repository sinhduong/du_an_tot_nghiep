<?php

namespace App\Http\Requests;

use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;

class StaffFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255',
                $this->isMethod('post')
                    ? 'unique:users,email'
                    : 'unique:users,email,' . Staff::find($this->staff)->user_id
            ],
            'password' =>  $this->isMethod('post') ? 'required|min:8' : 'nullable|min:8',
            'name' => 'required',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'note' => 'nullable|max:1000',
        ];
    }
}
