<?php

namespace Database\Seeders;

use App\Models\Link;
use Illuminate\Database\Seeder;
use Hashids\Hashids;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $links = Link::factory(10)->create();
        foreach ($links as $link) {
            $link->short_url = (new Hashids())->encode($link->id);
            $link->save();
        }
    }
}
