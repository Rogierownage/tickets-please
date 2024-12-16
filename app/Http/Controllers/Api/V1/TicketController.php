<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\DeleteTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketController extends ApiController
{
    /**
     * Ticket overview
     *
     * Display a paginated list of tickets.
     *
     * @group Ticket
     * @queryParam sort string The data fields to sort by. Comma separated. Descending sort is done by prefixing with a minus sign. Example: status,-title
     * @queryParam filter[status] Filter by status code. Available options: A, C, H, X. No-example
     * @queryParam filter[title] Filter by title. Wildcards are supported. Example: *Refinement*
     */
    public function index(TicketFilter $filters): AnonymousResourceCollection
    {
        $query = Ticket::filter($filters);

        if ($this->include('author')) {
            $query->with('user');
        }

        return TicketResource::collection($query->paginate());
    }

    /**
     * Ticket store
     *
     * Create a new ticket. The ticket's author is set to the authenticated user, except for managers, who must supply the author_id.
     *
     * @group Ticket
     */
    public function store(StoreTicketRequest $request)
    {
        $postedData = $request->mappedAttributes();

        if (empty($postedData['user_id'])) {
            $postedData['user_id'] = $request->user()->id;
        }

        $ticket = Ticket::create($postedData);

        return new TicketResource($ticket);
    }

    /**
     * Ticket show
     *
     * Display a single ticket's details.
     *
     * @group Ticket
     */
    public function show(Ticket $ticket) : TicketResource
    {
        if ($this->include('author')) {
            return new TicketResource($ticket->load('user'));
        }

        return new TicketResource($ticket);
    }

    /**
     * Ticket overview
     *
     * Display a paginated list of tickets.
     *
     * @group Ticket
     */
    public function update(Ticket $ticket, UpdateTicketRequest $request): TicketResource
    {
        $ticket->update($request->mappedAttributes());

        return new TicketResource($ticket);
    }

    /**
     * Ticket overview
     *
     * Display a paginated list of tickets.
     *
     * @group Ticket
     *
     * @SuppressWarnings(PHPMD)
     */
    public function destroy(Ticket $ticket, DeleteTicketRequest $request)
    {
        $ticket->delete();

        $this->ok();
    }
}
