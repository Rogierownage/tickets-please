<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class UpdateTicketRequest extends BaseTicketRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        if (!$this->user()->tokenCan(Abilities::TICKET_UPDATE_ANY)) {
            $rules['data.relationships.author.data.id'] = 'prohibited';
        }

        return $rules;
    }

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('ticket'));
    }
}
