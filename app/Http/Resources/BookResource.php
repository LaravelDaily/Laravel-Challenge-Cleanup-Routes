<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->when(
                $request->route()->parameter('book'),
                $this->description
            ),
            'cover' => $this->cover->getUrl('cover'),
            'authors' => $this->authors()->pluck('name')->implode(', '),
            'genres' => $this->genres()->pluck('name')->implode(', ')
        ];
    }
}
