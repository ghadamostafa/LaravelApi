<?php

namespace Database\Factories;

use App\Models\product;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

class productFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph(1),
            'quantity' => $this->faker->numberBetween(1,100),
            'status'=>$this->faker->randomElement([product::Available_product,product::Unavailable_product]),
            'image' =>$this->faker->randomElement(['1.jpg','2.jpg','3.jpg']),
            'seller_id' => User::all()->random()->id,

        ];
    }
}
