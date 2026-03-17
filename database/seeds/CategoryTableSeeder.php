<?php

use Illuminate\Database\Seeder;

use App\Category;
use Illuminate\Support\Str;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        foreach (range(1, 10) as $value) {
            Category::create([
                'name' => $faker->name,
                'slug' => Str::slug($faker->name),
            ]);
        }
    }
}
