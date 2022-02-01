<?php

namespace App\Services\Links\Handlers;

use App\Models\Link;
use App\Services\Links\Exceptions\LinksUrlException;
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
        try {
            $content = file_get_contents($data['long_url']);
        } catch (\Exception $exception) {
            throw new LinksUrlException('Ссылка '. $data['long_url'] .' не доступна.');
        }

        if (empty($data['title'])) {
            $data['title'] = $this->getTitle($content);
        }

        $link = $this->linksRepository->create($data);
        $link->saveTags($data['tags'] ?? []);
        $link->short_url = (new Hashids())->encode($link->id);
        $link->save();

        return $link;
    }

    private function getTitle(string $content): string
    {
        $matches = [];
        preg_match('~<title>(.*)</title>~is', $content, $matches);

        return $matches[1];
    }
}
