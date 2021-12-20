<?php

namespace Database\Factories;

use App\Models\Result;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Result::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'field_id' => $this->faker->word,
        'passed' => $this->faker->word,
        'accepted' => $this->faker->word,
        'value1' => $this->faker->word,
        'value2' => $this->faker->word,
        'value3' => $this->faker->word,
        'value4' => $this->faker->word,
        'value5' => $this->faker->word,
        'value6' => $this->faker->word,
        'value7' => $this->faker->word,
        'value8' => $this->faker->word,
        'value9' => $this->faker->word,
        'value10' => $this->faker->word,
        'value11' => $this->faker->word,
        'value12' => $this->faker->word,
        'value13' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
