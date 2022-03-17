<?php

namespace App\Services\Links;

use App\Http\Resources\Links\LinkResource;
use App\Models\Link;
use App\Models\View;
use App\Services\Links\Exceptions\LinksUrlException;
use App\Services\Links\Handlers\LinksCreateHandler;
use App\Services\Links\Handlers\LinksUpdateHandler;
use App\Services\Links\Repositories\LinksRepository;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LinksService
{
    private LinksCreateHandler $linksCreateHandler;
    private LinksRepository $linksRepository;
    private LinksUpdateHandler $linksUpdateHandler;

    public function __construct(
        LinksCreateHandler $linksCreateHandler,
        LinksUpdateHandler $linksUpdateHandler,
        LinksRepository    $linksRepository
    )
    {
        $this->linksCreateHandler = $linksCreateHandler;
        $this->linksRepository = $linksRepository;
        $this->linksUpdateHandler = $linksUpdateHandler;
    }

    public function getAll()
    {
        return $this->linksRepository->getAll();
    }

    public function store(Request $request): JsonResource
    {
        if (!array_is_list($request->all())) {
            $data = $request->validate([
                'long_url' => 'required|url|max:255',
                'title' => 'sometimes|string|max:255',
                'tags' => 'sometimes|array',
            ]);

            try {
                $content = $this->getContent($data['long_url']);
                $link = $this->createOneLink($data, $content);
            } catch (LinksUrlException $exception) {
                throw ValidationException::withMessages([$exception->getMessage()]);
            }

            return new LinkResource($link);
        }

        $data = $request->validate([
            '*.long_url' => 'required|url|max:255',
            '*.title' => 'sometimes|string|max:255',
            '*.tags' => 'sometimes|array',
        ]);
        $messages = [];
        $links = [];
        foreach ($data as $itemData) {
            try {
                $content = $this->getContent($itemData['long_url']);
                $links[] = $this->createOneLink($itemData, $content);
            } catch (LinksUrlException $exception) {
                $messages[] = $exception->getMessage();
            }
        }

        if (!empty($messages)) {
            throw ValidationException::withMessages($messages);
        }

        return LinkResource::collection($links);
    }

    public function update(Link $link, Request $request): JsonResource
    {
        $data = $request->validate([
            'long_url' => 'sometimes|url|max:255',
            'title' => 'sometimes|string|max:255',
            'tags' => 'sometimes|array',
        ]);
        if (!empty($data['long_url'])) {
            try {
                $this->getContent($data['long_url']);
            } catch (LinksUrlException $exception) {
                throw ValidationException::withMessages([$exception->getMessage()]);
            }
        }

        $link = $this->linksUpdateHandler->handle($link, $data);

        return new LinkResource($link);
    }

    public function delete(Link $link): void
    {
        $this->linksRepository->delete($link);
    }

    public function saveView(string $shortUrl, Request $request): View
    {
        $link = $this->linksRepository->getByShortUrl($shortUrl);
        if ($link === null) {
            throw new NotFoundHttpException('Ссылка ' . $shortUrl . ' не найдена.');
        }

        return $this->linksRepository->saveView($link['id'], $request->header('user-agent'), $request->ip());
    }

    public function getByTag(string $tag)
    {
        $link = $this->linksRepository->getByTag($tag);

        return $link;
    }

    private function getContent(string $longUrl): string
    {
        try {
            $content = file_get_contents($longUrl);
        } catch (ErrorException $e) {
            throw new LinksUrlException('Ссылка ' . $longUrl . ' не доступна.');
        }

        return $content;
    }

    private function getTitle(string $content): string
    {
        $matches = [];
        preg_match('~<title>(.*)</title>~is', $content, $matches);

        return $matches[1];
    }

    private function createOneLink(array $data, string $content): Link
    {
        if (empty($data['title'])) {
            $data['title'] = $this->getTitle($content);
        }

        return $this->linksCreateHandler->handle($data);
    }
}
