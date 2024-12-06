<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters): AnonymousResourceCollection
    {
        $query = Ticket::filter($filters);

        if ($this->include('author')) {
            $query->with('user');
        }

        return TicketResource::collection($query->paginate());
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create($request->mappedAttributes());

        return new TicketResource($ticket);
    }

    public function show(Ticket $ticket) : TicketResource
    {
        if ($this->include('author')) {
            return new TicketResource($ticket->load('user'));
        }

        return new TicketResource($ticket);
    }

    public function update(Ticket $ticket, UpdateTicketRequest $request): TicketResource
    {
        $ticket->update($request->mappedAttributes());

        return new TicketResource($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        $this->ok();
    }
}
