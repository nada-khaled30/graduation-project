<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegistrationRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'first_name'             => 'required|string|max:255',
            'last_name'              => 'required|string|max:255',
            'gender'                 => 'required|in:female,male,other',
            'specialist'             => 'required|in:dentist,cardiologist,dermatologist,pediatrician',
            'email'                  => 'required|email|unique:users,email',
            'mobile'                 => 'required|unique:users,mobile|max:15',
            'user_id'                => 'required|digits:7|unique:users,user_id',
            'password'               => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => 'required'
        ];
    }
}