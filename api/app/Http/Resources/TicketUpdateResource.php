<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketUpdateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'text' => $this->text,
            'is_internal' => $this->is_internal,
            'created_by_user' => new UserResource($this->whenLoaded('createdBy')),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}
