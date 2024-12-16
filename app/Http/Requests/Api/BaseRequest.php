<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

abstract class BaseRequest extends FormRequest
{
    protected string $policyClass;

    public function __construct()
    {
        if (isset($this->policyClass)) {
            Gate::guessPolicyNamesUsing(fn () => $this->policyClass);
        }

        parent::construct();
    }
}
