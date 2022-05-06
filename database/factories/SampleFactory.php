<?php

namespace Database\Factories;

use App\Models\Sample;
use Illuminate\Database\Eloquent\Factories\Factory;

class SampleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sample::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'point_id' => $this->faker->word,
        'date_selected' => $this->faker->date('Y-m-d H:i:s'),
        'date_received' => $this->faker->date('Y-m-d H:i:s'),
        'quantity' => $this->faker->randomDigitNotNull,
        'passed' => $this->faker->word,
        'accepted' => $this->faker->word,
        'notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
