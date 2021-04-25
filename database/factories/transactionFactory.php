<?php

namespace Database\Factories;

use App\Models\transaction;
use App\Models\User;
use App\Models\seller;
// use App\Models\buyer;

use Illuminate\Database\Eloquent\Factories\Factory;

class transactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $seller=seller::has('products')->get()->random();
        $buyer=User::all()->except($seller->id)->random();
        return [
            //
            'quantity' =>$this->faker->numberBetween(1,100),
            'buyer_id' => $buyer->id,
            'product_id' => $seller->products->random()->id
        ];
    }
}
