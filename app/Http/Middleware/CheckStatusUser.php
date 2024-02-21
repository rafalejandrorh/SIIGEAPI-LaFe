<?php

namespace App\Http\Middleware;

use App\Http\Constants;
use App\Traits\APITrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStatusUser
{
    use APITrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::user()->status) {
            $accept = $request->header('accept');
            $bearerToken = $request->bearerToken();

            if(isset($bearerToken) && $accept == 'application/json') {
                $this->setCode(Constants::HTTP_CODE_UNAUTHORIZED);
                $this->setDescription(Constants::DESCRIPTION_ERROR_INACTIVE_USER);
                return response()->json($this->getResponse(), $this->getcode());
            }

            $request['id'] = 4;
            return redirect()->route('logout.forced', $request['id']);
        }
        return $next($request);
    }
}
