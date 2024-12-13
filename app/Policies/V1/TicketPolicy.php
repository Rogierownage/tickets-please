<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;

class TicketPolicy
{
    public function create(User $user) : bool
    {
        return $user->tokenCan(Abilities::TICKET_CREATE_ANY) || $user->tokenCan(Abilities::TICKET_CREATE_OWN);
    }

    public function update(User $user, Ticket $ticket)
    {
        if ($user->tokenCan(Abilities::TICKET_UPDATE_ANY)) {
            return true;
        }

        return $user->tokenCan(Abilities::TICKET_UPDATE_OWN) && $ticket->user->is($user);
    }

    public function delete(User $user, Ticket $ticket)
    {
        if ($user->tokenCan(Abilities::TICKET_DELETE_ANY)) {
            return true;
        }

        return $user->tokenCan(Abilities::TICKET_DELETE_OWN) && $ticket->user->is($user);
    }
}
