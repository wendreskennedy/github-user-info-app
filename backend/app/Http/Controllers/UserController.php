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
        try {
            $user = $this->service->getUser($username);

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter usuário.'], $e->getCode());
        }
    }

    public function getFollowings(string $username): JsonResponse
    {
        try {

            $followings = $this->service->getFollowings($username);

            return response()->json($followings);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao obter followings.'], $e->getCode());
        }
    }
}
