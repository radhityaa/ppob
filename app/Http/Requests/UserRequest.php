<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'username' => 'required|string|min:3|max:20|regex:/^[a-zA-Z]+$/|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:15|min:8|unique:users',
            'saldo' => 'required|string',
            'status' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username is required!',
            'username.min' => 'Username must be at least 3 characters!',
            'username.max' => 'Username can be up to 20 characters!',
            'username.unique' => 'Username is already taken!',
            'username.regex' => 'Username can only contain letters (A-Z or a-z) without spaces or numbers!',
        ];
    }
}
