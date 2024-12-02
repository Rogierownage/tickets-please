<?php

namespace App\Http\Filters\V1;

class TicketFilter extends QueryFilter
{
    protected array $sortable = ['title', 'status', 'created_at', 'updated_at'];

    public function status(string $value)
    {
        $this->builder->whereIn('status', explode(',', $value));
    }

    public function title(string $value)
    {
        $likeString = str_replace('*', '%', $value);

        $this->builder->where('title', 'like', $likeString);
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
