<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterStoreRequest;
use App\Models\User;
use App\Services\Api\ApiAnswerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function store(RegisterStoreRequest $request): JsonResponse
    {
        $fields = $request->validated();
        $fields['password'] = Hash::make($fields['password']);

        $user = User::create($fields);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'user was not created']);
        }

        $token = $user->createToken('login token');
        return ApiAnswerService::successfulAnswerWithData(['token' => $token->plainTextToken]);
    }
}
