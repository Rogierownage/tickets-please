<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;

class AuthorTicketController extends ApiController
{
    public function index(User $author, TicketFilter $filters)
    {
        $query = $author->tickets()->filter($filters);

        return TicketResource::collection($query->paginate());
    }

    public function store(User $author, StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $author->id,
        ]);

        return new TicketResource($ticket);
    }
}
