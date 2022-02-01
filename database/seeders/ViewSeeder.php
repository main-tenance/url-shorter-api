<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\View;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;

class ViewSeeder extends Seeder
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
                View::create([
                    'link_id' => $link->id,
                    'user_agent' => $faker->userAgent(),
                    'user_ip' => $faker->ipv4(),
                ]);
            }
        }
    }
}
