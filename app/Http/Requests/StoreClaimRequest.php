<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClaimRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $item = $this->route('item');

        return $this->user() !== null && $item !== null && $this->user()->can('claim', $item);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'proof_description' => ['required', 'string', 'min:10', 'max:5000'],
            'proof_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'verification_answers' => ['nullable', 'array'],
            'verification_answers.*' => ['nullable', 'string', 'max:1000'],
        ];

        $item = $this->route('item');
        if ($item && $item->verificationQuestions()->exists()) {
            foreach ($item->verificationQuestions as $question) {
                $rules["verification_answers.{$question->id}"] = ['required', 'string', 'max:1000'];
            }
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = [];
        $item = $this->route('item');
        if ($item && $item->verificationQuestions()->exists()) {
            foreach ($item->verificationQuestions as $question) {
                $attributes["verification_answers.{$question->id}"] = "answer to '{$question->question}'";
            }
        }

        return $attributes;
    }
}
