<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        if (!$param) {
            return false;
        }

        $includedValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includedValues);
    }
}