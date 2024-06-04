<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required',
            'button_text' => 'required',
            'button_url' => 'required',
            'small_text' => 'required',
            'image_hero_dashboard' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'image_hero_element' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ];
    }
}
