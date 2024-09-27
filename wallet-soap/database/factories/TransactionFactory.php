<?php

namespace Database\Factories;

use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElements(TransactionTypeEnum::cases()),
            'session_id' => fake()->regexify('[A-Za-z0-9]{16}'),
            'status' => fake()->randomElements(TransactionStatusEnum::cases()),
            'amount' => fake()->randomFloat(2, 1, 1000),
            'token' => fake()->uuid(),
            'expires_at' => fake()->dateTimeBetween('now', '+10 minutes'),
            'confirmed_at' => fake()->dateTimeBetween('now', '+10 minutes'),
        ];
    }
}
