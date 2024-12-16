<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseRequest;
use App\Policies\V1\TicketPolicy;
use Illuminate\Validation\Rule;

class BaseTicketRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct();

        $this->policyClass = TicketPolicy::class;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $requiredRule = $this->route('ticket') ? 'nullable' : 'required';

        $rules = [
            'data' => ['required', 'array'],
            'data.attributes' => ['required', 'array'],
            'data.attributes.title' => [$requiredRule, 'string'],
            'data.attributes.description' => [$requiredRule, 'string'],
            'data.attributes.status' => [$requiredRule, 'string', Rule::in(['A','C','H','X'])],
        ];

        if ($this->routeIs('ticket.*')) {
            $rules = array_merge($rules, [
                'data' => [$requiredRule, 'array'],
                'data.relationships' => [$requiredRule, 'array'],
                'data.relationships.author' => [$requiredRule, 'array'],
                'data.relationships.author.data' => [$requiredRule, 'array'],
                'data.relationships.author.data.id' => [$requiredRule, 'integer', 'exists:users,id'],
            ]);
        }

        return $rules;
    }

    public function bodyParameters()
    {
        $documentation = [
            'data.attributes.title' => [
                'description' => 'The ticket\'s title.',
                'example' => null,
            ],
        ];

        if ($this->routeIs('ticket.*')) {
            $documentation = array_merge($documentation, [
                'data.relationships.author.data.id' => [
                    'description' => 'The author to assign the ticket to.',
                ],
            ]);
        }

        return $documentation;
    }

    public function messages() : array
    {
        return [
            'data.attributes.status' => 'The selected value is invalid. Valid options are: A, C, H, X',
        ];
    }

    public function mappedAttributes(): array
    {
        $mapping = [
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.relationships.author.data.id' => 'user_id',
        ];

        $mappedData = [];

        foreach ($mapping as $key => $attribute) {
            $value = $this->input($key);

            if ($value) {
                $mappedData[$attribute] = $value;
            }
        }

        return $mappedData;
    }
}
