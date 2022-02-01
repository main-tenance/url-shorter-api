<?php

namespace App\Services\Links\Handlers;

use App\Models\Link;
use App\Services\Links\Exceptions\LinksUrlException;
use App\Services\Links\Repositories\LinksRepository;

class LinksUpdateHandler
{
    private LinksRepository $linksRepository;

    public function __construct(
        LinksRepository $linksRepository
    )
    {
        $this->linksRepository = $linksRepository;
    }

    public function handle(Link $link, array $data): Link
    {
        if (!empty($data['long_url'])) {
            try {
                $content = file_get_contents($data['long_url']);
                $link->long_url = $data['long_url'];
            } catch (\Exception $exception) {
                throw new LinksUrlException('Ссылка '. $data['long_url'] .' не доступна.');
            }
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
