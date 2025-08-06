<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'        => $this->name,
            'email'         => $this->email,
            'tenant'         => $this->tenant_id,
            'created_at'   => date("d-m-Y",strtotime($this->created_at)),
            'updated_at'   => date("d-m-Y",strtotime($this->updated_at)),
        ];
    }
}
