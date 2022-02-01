<?php

namespace Tests\Generators;

use App\Models\Link;
use App\Models\Tag;
use App\Models\View;
use Faker\Generator;
use Hashids\Hashids;
use Illuminate\Container\Container;

class LinkGenerator
{
    public static function create(int $cnt = 1): void
    {
        $faker = Container::getInstance()->make(Generator::class);
        Link::factory($cnt)->create();
        foreach (Link::all() as $link) {
            $link->short_url = (new Hashids())->encode($link->id);
            $link->save();
            $count = $faker->randomDigitNotNull();
            for ($i = 0; $i < $count; $i++) {
                Tag::create([
                    'link_id' => $link->id,
                    'name' => $faker->word(),
                ]);
            }
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

    public static function getData()
    {
        $faker = Container::getInstance()->make(Generator::class);
        $tagsCount = $faker->randomDigitNotNull();
        return [
            'long_url' => 'https://google.com',
            'title' => $faker->word(),
            'tags' => $faker->words($nb = $tagsCount, $asText = false) ,
        ];
    }

}
