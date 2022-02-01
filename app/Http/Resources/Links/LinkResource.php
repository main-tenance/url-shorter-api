<?php

namespace App\Http\Resources\Links;

use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'long_url' => $this->long_url,
            'short_url' => $this->short_url,
            'title' => $this->title,
            'tags' => new TagsResource($this->tags),
        ];
    }
}
