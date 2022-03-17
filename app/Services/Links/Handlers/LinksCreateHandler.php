<?php

namespace App\Services\Links\Handlers;

use App\Models\Link;
use App\Services\Links\Repositories\LinksRepository;
use Hashids\Hashids;

class LinksCreateHandler
{
    private LinksRepository $linksRepository;

    public function __construct(
        LinksRepository $linksRepository
    )
    {
        $this->linksRepository = $linksRepository;
    }

    public function handle(array $data): Link
    {
        $link = $this->linksRepository->create($data);
        $link->saveTags($data['tags'] ?? []);
        $link->short_url = (new Hashids())->encode($link->id);
        $link->save();

        return $link;
    }


}
