<?php

namespace App\Http\Requests\Api\V1;

class DeleteTicketRequest extends BaseTicketRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('delete', $this->route('ticket'));
    }
}
