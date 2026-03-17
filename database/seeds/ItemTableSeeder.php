<?php

use Illuminate\Database\Seeder;

use App\Item;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $i = 1;
        foreach (range(1, 10) as $value) {
            Item::create([
                'category_id' => $i++,
                'name' => $faker->name,
                'description' => 'Would YOU like cats if you were never even spoke to Time',
                'price' => rand(1, 5),
                'image' => rand(1, 10).'.jpg',
            ]);
        }
    }
}
