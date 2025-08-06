<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'body'         => $this->body,
            'category'     => new CategoryResource($this->whenLoaded('category')),
            'featured_img' => $this->featured_image,
            'created_by'   => new UserResource($this->creator),
            'updated_by'   =>  new UserResource($this->updater),
            'created_at'   => date("d-m-Y",strtotime($this->created_at)),
            'updated_at'   => date("d-m-Y",strtotime($this->updated_at)),
        ];
    }


}
