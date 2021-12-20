<?php

namespace Database\Factories;

use App\Models\Subpolygon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubpolygonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subpolygon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'polygon_id' => $this->faker->word,
        'geometry' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
