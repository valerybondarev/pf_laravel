<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\TokenCreateRequest;
use Illuminate\Http\JsonResponse;

class TokenController extends Controller
{
    /**
     * @param  TokenCreateRequest  $request
     * @return JsonResponse
     */
    public function create(TokenCreateRequest $request): JsonResponse
    {
        if (!$token = auth('api')->attempt([
            'email' => $request->input('login'),
            'password' => $request->input('password'),
        ])) {
            return response()->json([
                'status' => false,
                'message' => 'Please login in API',
                'data' => null
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => null,
            'data' => [
                'token' => $token,
                'start' => time(),
                'expired' => time() + auth('api')->factory()->getTTL() * 60
            ]
        ], 200);
    }
}
