<?php

namespace App\Http\Controllers;

use App\Http\Resources\Links\LinkResource;
use App\Models\Link;
use App\Models\Tag;
use App\Services\Links\LinksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkController extends Controller
{
    private LinksService $service;

    public function __construct(LinksService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResource
     */
    public function index()
    {
        $links = $this->service->getAll();

        return LinkResource::collection($links);
    }

    /**LinksResource
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource
    {
        return $this->service->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  Link  $link
     * @return JsonResource
     */
    public function show(Link $link)
    {
        return new LinkResource($link);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Link  $link
     * @return JsonResource
     */
    public function update(Request $request, Link $link)
    {
        $link = $this->service->update($link, $request);

        return new LinkResource($link);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Link  $link
     * @return JsonResponse
     */
    public function destroy(Link $link)
    {
        $this->service->delete($link);

        return response()->json(['status' => 'ok']);
    }

    public function view(string $shortUrl, Request $request)
    {
        $view = $this->service->saveView($shortUrl, $request);

        return redirect()->away($view->link->long_url);
    }

    public function title(Link $link): JsonResource
    {
        return new LinkResource($link);
    }

    public function tag(string $tag): JsonResource
    {
        $link = $this->service->getByTag($tag);

        return new LinkResource($link);
    }
}
