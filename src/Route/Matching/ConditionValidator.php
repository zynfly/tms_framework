<?php

namespace Themosis\Route\Matching;

use Illuminate\Http\Request;
use Illuminate\Routing\Matching\ValidatorInterface;
use Illuminate\Routing\Route;

class ConditionValidator implements ValidatorInterface
{
    /**
     * Validate a given rule against a route and request.
     *
     * @param \Illuminate\Routing\Route $route
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function matches(Route $route, Request $request)
    {
        $condition = $route->getCondition();
        static $conditionMatches = [];

        if (isset($conditionMatches[$condition])) {
            return $conditionMatches[$condition];
        }
        $conditionMatches[$condition] = function_exists($condition) ? call_user_func_array($condition, $route->getConditionParameters()) : false;

        return $conditionMatches[$condition];
    }
}
