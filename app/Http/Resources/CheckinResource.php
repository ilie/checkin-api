<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckinResource extends JsonResource
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
            'type' => 'checkin',
            'id' => (string) $this->resource->getRouteKey(),
            'attributes' => [
                'checkin_date' => $this->resource->checkin_date,
                'checkin_time' => $this->resource->checkin_time,
                'checkout_time' => $this->resource->checkout_time,
                'created_at' => $this->resource->created_at->toDateTimeString(),
                'updated_at' => $this->resource->updated_at->toDateTimeString(),
                'user_id' => $this->resource->user->id,
                'user_name' => $this->resource->user->name,
            ],
            'links' => [
                'self' => route('checkins.show', $this->resource)
            ]
        ];
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return parent::toResponse($request)->withHeaders([
            'Location' => route('checkins.show', $this->resource)
        ]);
    }
}
