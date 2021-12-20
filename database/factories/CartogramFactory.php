<?php

namespace Database\Factories;

use App\Models\Cartogram;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartogramFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cartogram::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'field_id' => $this->faker->word,
        'status' => $this->faker->randomElement(['completed']),
        'access_url' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
