<?php

namespace App\Services\Links;

use App\Http\Resources\Links\LinkResource;
use App\Models\Link;
use App\Models\View;
use App\Services\Links\Exceptions\LinksUrlException;
use App\Services\Links\Handlers\LinksCreateHandler;
use App\Services\Links\Handlers\LinksUpdateHandler;
use App\Services\Links\Repositories\LinksRepository;
use Illuminate\Http\RedirectResponse;
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
        LinksRepository $linksRepository
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
                $link = $this->linksCreateHandler->handle($data);
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
                $links[] = $this->linksCreateHandler->handle($itemData);
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
            throw new NotFoundHttpException('Ссылка '.$shortUrl.' не найдена.');
        }

        return $this->linksRepository->saveView($link['id'], $request->header('user-agent'), $request->ip());
    }

    public function getByTag(string $tag)
    {
        $link = $this->linksRepository->getByTag($tag);

        return $link;
    }
}
