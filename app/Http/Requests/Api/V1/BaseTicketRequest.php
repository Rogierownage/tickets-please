<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\BaseRequest;
use App\Models\User;
use App\Policies\V1\TicketPolicy;
use Illuminate\Validation\Rule;

class BaseTicketRequest extends BaseRequest
{
    public function __construct()
    {
        $this->policyClass = TicketPolicy::class;

        parent::__construct();
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $requiredRule = $this->route('ticket') ? 'nullable' : 'required';

        $userIds = User::pluck('id')->toArray();
        $rules = [
            'data.attributes.title' => [$requiredRule, 'string'],
            'data.attributes.description' => [$requiredRule, 'string'],
            'data.attributes.status' => [$requiredRule, 'string', Rule::in(['A','C','H','X'])],
        ];

        if ($this->routeIs('ticket.*')) {
            $rules['data.relationships.author.data.id'] = [$requiredRule, 'integer', Rule::in($userIds)];
        }

        return $rules;
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
