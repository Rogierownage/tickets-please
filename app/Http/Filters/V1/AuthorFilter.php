<?php

namespace App\Http\Filters\V1;

class AuthorFilter extends QueryFilter
{
    protected array $sortable = ['name', 'email', 'created_at', 'updated_at'];

    public function id(string $value)
    {
        $this->builder->whereIn('id', explode(',', $value));
    }

    public function email(string $value)
    {
        $likeString = str_replace('*', '%', $value);

        $this->builder->where('email', 'like', $likeString);
    }

    public function name(string $value)
    {
        $likeString = str_replace('*', '%', $value);

        $this->builder->where('name', 'like', $likeString);
    }

    public function createdAt(string $value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function updatedAt(string $value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}
