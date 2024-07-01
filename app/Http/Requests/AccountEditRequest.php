<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountEditRequest extends FormRequest
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
            'name' => 'string|max:255',
            'email' => 'email|max:255|unique:users,email,' . $this->user->id,
            'phone' => 'min:10|max:14|unique:users,phone,' . $this->user->id,
            'shop_name' => 'string|max:255',
            'address' => 'string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}
