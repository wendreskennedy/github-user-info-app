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
            $statusCode = $e->getCode() > 0 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json(['error' => 'Erro ao obter usuario.'], $statusCode);
        }
    }

    public function getFollowings(string $username): JsonResponse
    {
        try {

            $followings = $this->service->getFollowings($username);

            return response()->json($followings);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() > 0 && $e->getCode() < 600 ? $e->getCode() : 500;
            return response()->json(['error' => 'Erro ao obter followings do usuario.'], $statusCode);
        }
    }
}
