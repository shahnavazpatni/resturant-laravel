<?php

use Illuminate\Database\Seeder;

use App\Slider;

class SliderTableSeeder extends Seeder
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
            Slider::create([
                'title' => $faker->text,
                'sub_title' => 'Would YOU like cats if you were never even spoke to Time',
            ]);
        }
    }
}
