<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'type' => 'user',
            'id' => (string) $this->resource->getRouteKey(),
            'attributes' => [
                'name' => $this->resource->name,
                'nif' => $this->resource->nif,
                'email' => $this->resource->email,
                'social_sec_num' => $this->resource->social_sec_num,
                'hours_on_contract' => $this->resource->hours_on_contract,
                'is_admin' => $this->resource->is_admin,
                'created_at' => $this->resource->created_at->toDateTimeString(),
                'updated_at' => $this->resource->updated_at->toDateTimeString(),
            ],
            'links' => [
                'self' => route('users.show', $this->resource)
            ]
        ];
    }
}
