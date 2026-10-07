<?php

namespace App\Http\Requests;

use App\Enums\ItemType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'type' => ['required', new Enum(ItemType::class)],
            'category_id' => ['required', 'exists:categories,id'],
            'date_lost_or_found' => ['required', 'date', 'before_or_equal:today'],
            'location_name' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'reward_amount' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'is_anonymous' => ['nullable', 'boolean'],
            'primary_image' => ['nullable', 'file', 'extensions:jpeg,jpg,png,webp,gif', 'max:5120'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['file', 'extensions:jpeg,jpg,png,webp,gif', 'max:5120'],
            'verification_questions' => ['nullable', 'array', 'max:5'],
            'verification_questions.*' => ['nullable', 'string', 'max:500'],
        ];
    }
}
