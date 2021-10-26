<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use App\Traits\ApiResponser;

class CustomThrottleRequests extends ThrottleRequests
{
    use ApiResponser;

    protected function buildResponse($key,$maxAttempts){
        $response=$this->errorResponse('Too many attempts.',429);
        $retryAfter=$this->limiter->availableIn($key);
        return $this->addHeaders(
          $response,$maxAttempts,
          $this->calculateRemainingAttempts($key,$maxAttempts,$retryAfter),
          $retryAfter
        );
    }
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
