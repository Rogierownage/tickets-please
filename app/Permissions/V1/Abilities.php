<?php

namespace App\Permissions\V1;

use App\Models\User;

class Abilities
{
    public const TICKET_CREATE_ANY = 'ticket.create_any';
    public const TICKET_CREATE_OWN = 'ticket.create_own';
    public const TICKET_UPDATE_ANY = 'ticket.update_any';
    public const TICKET_UPDATE_OWN = 'ticket.update_own';
    public const TICKET_DELETE_ANY = 'ticket.delete_any';
    public const TICKET_DELETE_OWN = 'ticket.delete_own';

    public static function forUser(User $user) : array
    {
        if ($user->is_manager) {
            return [
                self::TICKET_CREATE_ANY,
                self::TICKET_UPDATE_ANY,
                self::TICKET_DELETE_ANY,
            ];
        }

        return [
            self::TICKET_CREATE_OWN,
            self::TICKET_UPDATE_OWN,
            self::TICKET_DELETE_OWN
        ];
    }
}
