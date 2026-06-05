<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'created_by_user' => new UserResource($this->whenLoaded('createdBy')),
            'assigned_user' => new UserResource($this->whenLoaded('assigned')),
            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
                'is_finalised' => $this->status->isFinalised(),
            ],
            'priority' => [
                'value' => $this->priority->value,
                'label' => $this->priority->label(),
            ],
            'company' => new CompanyResource($this->whenLoaded('company')),
            'site' => new SiteResource($this->whenLoaded('site')),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}
