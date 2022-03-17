<?php

namespace App\Services\Links\Handlers;

use App\Models\Link;

class LinksUpdateHandler
{
    public function handle(Link $link, array $data): Link
    {
        if (!empty($data['long_url'])) {
            $link->long_url = $data['long_url'];
        }

        if (!empty($data['title'])) {
            $link->title = $data['title'];
        }

        if ($link->isDirty()) {
            $link->save();
        }

        if (!empty($data['tags'])) {
            $link->saveTags($data['tags']);
        }

        return $link;
    }
}
