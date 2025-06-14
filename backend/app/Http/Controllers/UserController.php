<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function getUser(string $username): JsonResponse
    {
        $user = $this->service->getUser($username);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        return response()->json($user);
    }

    public function getFollowings(string $username): JsonResponse
    {
        $followings = $this->service->getFollowings($username);

        if (!$followings) {
            return response()->json(['error' => 'Não foi possível obter os followings.'], 404);
        }

        return response()->json($followings);
    }
}
