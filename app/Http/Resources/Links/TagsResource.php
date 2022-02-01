<?php

namespace App\Http\Resources\Links;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TagsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable
     */
    public function toArray($request)
    {
        return [
            'items' => $this->collection->map(fn($item) => new TagResource($item)),
        ];
    }
}
