<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $token = $request->cookie('token');

            if (!$token) {
                // Log::warning('JWT ausente', [
                //     'ip' => $request->ip(),
                //     'url' => $request->fullUrl(),
                //     'method' => $request->method(),
                // ]);
                return response()->json(['message' => 'Token ausente'], 401);
            }

            $payload = JWTAuth::setToken($token)->getPayload();
            $expTimestamp = $payload->get('exp');
            $expiresAt = Carbon::createFromTimestamp($expTimestamp);

            // Log::info('JWT recebido', [
            //     'ip' => $request->ip(),
            //     'url' => $request->fullUrl(),
            //     'method' => $request->method(),
            //     'exp' => $expiresAt->toDateTimeString(),
            //     'payload' => $payload->toArray(),
            // ]);

            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                // Log::warning('Usuário inválido', [
                //     'ip' => $request->ip(),
                //     'url' => $request->fullUrl(),
                // ]);
                return response()->json(['message' => 'Usuário inválido'], 401);
            }

            $request->setUserResolver(fn () => $user);

        } catch (TokenExpiredException $e) {
            // Log::warning('Token expirado', [
            //     'ip' => $request->ip(),
            //     'url' => $request->fullUrl(),
            // ]);
            return response()->json(['message' => 'Token expirado'], 401);
        } catch (TokenInvalidException $e) {
            // Log::warning('Token inválido', [
            //     'ip' => $request->ip(),
            //     'url' => $request->fullUrl(),
            // ]);
            return response()->json(['message' => 'Token inválido'], 401);
        } catch (Exception $e) {
            // Log::error('Erro ao autenticar', [
            //     'ip' => $request->ip(),
            //     'url' => $request->fullUrl(),
            //     'error' => $e->getMessage(),
            // ]);
            return response()->json(['message' => 'Erro ao autenticar'], 401);
        }

        return $next($request);
    }
}
