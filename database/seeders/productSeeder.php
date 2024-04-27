<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\product;
use Illuminate\Support\Facades\DB;

class productSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        for ($i = 0; $i < 100; $i++) {
            Product::create([
                'name' => 'Product ' . ($i + 1),
                'description' => 'Description of Product ' . ($i + 1),
                'imageURL' => 'https://example.com/image_' . ($i + 1) . '.jpg',
                'price' => rand(10, 1000),
                'rating' => rand(0, 5), 
            ]);
        }
        DB::table('products')
        ->where('id', '<=' , 35)
        ->update(['category_id'=>1]);
       
        DB::table('products')
        ->whereBetween('id', [36,70])
        ->update(['category_id'=>2]);

        DB::table('products')
        ->where('id', '>', 70)
        ->update(['category_id'=>3]);
    }
    

}
