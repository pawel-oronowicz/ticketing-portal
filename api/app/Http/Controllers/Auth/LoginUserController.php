<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class LoginUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginUserRequest $request, UserService $userService): JsonResponse
    {
        $user = $userService->authenticateUser($request->validated());

        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => new UserResource($user)
            ]);
        }

        return response()->json([
            'message' => 'Your credentials are incorrect'
        ], 403);
    }
}
