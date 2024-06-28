<?php

namespace Database\Factories;

use App\Models\Athlete;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory
{

    protected $model = Certificate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'athlete_id' => Athlete::factory(),
            'expires_on' => fake()->dateTimeBetween('-10 years', '+1 year'),
            'is_current' => false,
            'document' => null
        ];
    }
}
