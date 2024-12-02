<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $includes = new UserResource($this->whenLoaded('user'));

        return [
            'type' => 'ticket',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->when(
                    $request->routeIs('ticket.show'),
                    $this->description,
                ),
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user->id,
                    ],
                    'links' => [
                        'self' => route('author.show', ['author' => $this->user->id]),
                    ],
                ],
            ],
            'includes' => $includes,
            'links' => [
                'self' => route('ticket.show', ['ticket' => $this->id]),
            ]
        ];
    }
}
