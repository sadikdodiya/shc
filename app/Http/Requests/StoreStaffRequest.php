<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'alt_phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'joining_date' => 'required|date',
            'dob' => 'required|date|before:today',
            'aadhar_number' => 'required|string|max:20|unique:users,aadhar_number',
            'pan_number' => 'nullable|string|max:20|unique:users,pan_number',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:30',
            'ifsc_code' => 'required|string|max:20',
            'salary_type' => 'required|in:fixed,per_call,commission',
            'salary' => 'required|numeric|min:0',
            'salary_components' => 'nullable|array',
            'allow_part_deduction' => 'boolean',
            'is_active' => 'boolean',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:5120', // 5MB max per file
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'roles.required' => 'Please select at least one role for the staff member.',
            'documents.*.max' => 'Each document must not be larger than 5MB.',
            'documents.*.mimes' => 'Only JPG, PNG, PDF, and DOCX files are allowed.',
        ];
    }
}
