<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Transaction;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
       DB::statement('SET FOREIGN_KEY_CHECKS=0');
       User::truncate();
       Category::truncate();
       Product::truncate();
       Transaction::truncate();
       DB::table('category_product')->truncate();
       User::flushEventListeners();
       Category::flushEventListeners();
       Product::flushEventListeners();
       Transaction::flushEventListeners();
       $usersQuantity=1000;
       $categoriesQuantity=30;
       $transactionsQuantity=1000;
       $productsQuantity=1000;
       //store informations in database
      User::factory()->count($usersQuantity)->create();
      Category::factory()->count($categoriesQuantity)->create();
      Product::factory()->count($productsQuantity)->create()->each(

          function($product){
             $categories=Category::all()->random(mt_rand(1,5))->pluck('id');
             $product->categories()->attach($categories);
          }
       );
       Transaction::factory()->count($transactionsQuantity)->create();
    }
}
