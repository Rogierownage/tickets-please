<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

abstract class BaseRequest extends FormRequest
{
    protected string $policyClass;

    public function __construct()
    {
        if ($this->policyClass) {
            Gate::guessPolicyNamesUsing(function () {
                return $this->policyClass;
            });
        }
    }
}
