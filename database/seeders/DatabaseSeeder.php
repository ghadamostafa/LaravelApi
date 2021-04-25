<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\category;
use App\Models\product;
use App\Models\transaction;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        category::truncate();
        product::truncate();
        transaction::truncate();
        DB::table('category_product')->truncate();
        $usersQuantity=200;
        $categoriesQuantity=30;
        $productsQuantity=1000;
        $transactionsQuantity=1000;
        \App\Models\User::factory()->count($usersQuantity)->create(); 
        \App\Models\category::factory()->count($categoriesQuantity)->create(); 
        \App\Models\product::factory()->count($productsQuantity)->create()->each(
            function($product){
                $categories=category::all()->random(mt_rand(1,5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );
        \App\Models\transaction::factory()->count($transactionsQuantity)->create();
    }
}
