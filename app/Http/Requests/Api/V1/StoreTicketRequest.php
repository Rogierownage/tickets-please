<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Ticket;
use App\Permissions\V1\Abilities;
use Illuminate\Support\Facades\Auth;

class StoreTicketRequest extends BaseTicketRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        if (!Auth::user()->tokenCan(Abilities::TICKET_CREATE_ANY)) {
            $rules['data.relationships.author.data.id'] = 'prohibited';
        }

        return $rules;
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', Ticket::class);
    }
}
