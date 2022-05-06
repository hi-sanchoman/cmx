<?php

namespace Database\Factories;

use App\Models\Path;
use Illuminate\Database\Eloquent\Factories\Factory;

class PathFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Path::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date_started' => $this->faker->date('Y-m-d H:i:s'),
        'date_completed' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
