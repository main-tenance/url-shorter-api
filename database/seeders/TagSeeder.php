<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\Tag;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Container::getInstance()->make(Generator::class);
        foreach (Link::all() as $link) {
            $count = $faker->randomDigitNotNull();
            for ($i = 0; $i < $count; $i++) {
                Tag::create([
                    'link_id' => $link->id,
                    'name' => $faker->word(),
                ]);
            }
        }
    }
}
