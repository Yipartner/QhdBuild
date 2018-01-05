<?php

namespace App\Http\Middleware;

use App\Service\CatalogService;
use App\Service\TokenService;
use Carbon\Carbon;
use Closure;

class Permission
{
    private $tokenService;
    public function __construct(TokenService $tokenService)
    {
        $this->tokenService=$tokenService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->hasHeader('token')){
            $token = $this->tokenService->getToken($request->header('tokenId'));
            $time=new Carbon();
            if ($request->header('token')==$token&&$token->expired_at>$time)
                return $next($request);
            else
            {
                return response()->json([
                    'code'=>6001,
                    'message' => 'token无效'
                ]);
            }
        }
        else
            return response()->json([
                'code' => 6002,
                'message' => '没有token'
            ]);
    }
}
