<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginStoreRequest;
use App\Models\User;
use App\Services\Api\ApiAnswerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function store(LoginStoreRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return ApiAnswerService::errorAnswer('User not found', 404);
        }

        if(Hash::check($request->password, $user->password)) {
            $user->tokens()->delete();
            $token = $user->createToken('login token');
            return ApiAnswerService::successfulAnswerWithData(['token' => $token->plainTextToken]);
        }

        return ApiAnswerService::errorAnswer('Wrong email or password', 401);
    }

    public function destroy(): JsonResponse
    {
        $user = auth()->user();
        if($user) {
            $user->tokens()->delete();
            return ApiAnswerService::successfulAnswer();
        }

        return ApiAnswerService::errorAnswer('User not found', 404);
    }
}
