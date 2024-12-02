<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\User;

class AuthorTicketController extends ApiController
{
    public function index(User $author, TicketFilter $filters)
    {
        $query = $author->tickets()->filter($filters);

        return TicketResource::collection($query->paginate());
    }
}
