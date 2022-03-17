<?php

namespace App\Services\Links\Repositories;

use App\Models\Link;
use App\Models\Tag;
use App\Models\View;

class LinksRepository
{
    public function create(array $data): Link
    {
        return Link::create($data);
    }

    public function getAll()
    {
        return Link::all();
    }

    public function delete(Link $link): void
    {
        $link->delete();
    }

    public function getByShortUrl($shortUrl): Link
    {
        return Link::where('short_url', $shortUrl)->first();
    }

    public function getByTag(string $name): Link
    {
        $tag = Tag::where('name', $name)->first();
        $link = Link::where('id', $tag->link_id)->first();

        return $link;
    }

    public function saveView(int $linkId, string $userAgent, string $userIp): View
    {
        return View::create([
            'user_agent' => $userAgent,
            'user_ip' => $userIp,
            'link_id' => $linkId,
        ]);
    }
}
