<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;

abstract class QueryFilter
{
    protected Builder $builder;
    protected array $sortable = [];

    public function __construct(protected Request $request)
    {
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        $this->filter($this->request->filter ?? []);
        $this->sort($this->request->sort ?? '');
    }

    private function filter(array $data)
    {
        foreach ($data as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value ?? '');
            }
        }
    }

    private function sort(string $data)
    {
        if (empty($data)) {
            return;
        }

        foreach (explode(',', $data) as $sortAttribute) {
            $sortDirection = Str::startsWith($sortAttribute, '-') ? 'desc' : 'asc';
            $sortAttribute = Str::remove('-', $sortAttribute);

            if (!in_array($sortAttribute, $this->sortable)) {
                throw new InvalidArgumentException($sortAttribute . ' is not a sortable column.');
            }

            $this->builder->orderBy($sortAttribute, $sortDirection);
        }
    }
}
