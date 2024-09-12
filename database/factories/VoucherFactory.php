<?php

namespace Database\Factories;

use App\Enums\VoucherType;
use App\Models\Athlete;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class VoucherFactory extends Factory
{

    protected $model = Voucher::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'athlete_id' => Athlete::factory(),
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElements(VoucherType::getValues())[0],
            'amount' => $this->faker->numberBetween(2, 10)
        ];
    }
}
