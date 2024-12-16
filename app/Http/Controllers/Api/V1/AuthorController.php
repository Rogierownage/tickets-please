<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;

class AuthorController extends ApiController
{
    public function index(AuthorFilter $filters)
    {
        $query = User::filter($filters);

        if ($this->include('tickets')) {
            $query->with('tickets');
        }

        return UserResource::collection($query->paginate());
    }

    public function show(User $author)
    {
        if ($this->include('tickets')) {
            $author->load('tickets');
        }

        return new UserResource($author);
    }
}
