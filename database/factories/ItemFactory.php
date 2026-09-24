<?php

namespace Database\Factories;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(10),
            'type' => $this->faker->randomElement(ItemType::cases()),
            'status' => ItemStatus::ACTIVE,
            'date_lost_or_found' => $this->faker->date(),
            'location_name' => $this->faker->city(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'primary_image' => null,
            'contact_phone' => $this->faker->phoneNumber(),
            'contact_email' => $this->faker->safeEmail(),
            'reward_amount' => null,
            'is_anonymous' => false,
        ];
    }
}
