<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Cache;

class UserService
{
    protected $baseUrl = 'https://api.github.com/users/';

    public function getUser(string $username): ?array
    {
        $endpoint = $this->baseUrl . $username;

        $response = Cache::remember("github_user_{$username}", now()->addMinutes(30), function () use ($endpoint) {
            $response = Http::get($endpoint);

            if ($response->failed()) {
                throw new \Exception('Erro ao obter usuario.', $response->status());
            }
        });

        $this->logRequest([
            'method' => 'GET',
            'endpoint' => $endpoint,
            'payload' => null,
            'status_code' => $response->status(),
        ]);

        return $response->json();
    }

    public function getFollowings(string $username): ?array
    {
        $endpoint = $this->baseUrl . $username . '/following';
        $response = Cache::remember("github_followings_{$username}", now()->addMinutes(30), function () use ($endpoint) {
            $response = Http::get($endpoint);

            if ($response->failed()) {
                throw new \Exception('Erro ao obter followings.', $response->status());
            }
        });

        $this->logRequest([
            'method' => 'GET',
            'endpoint' => $endpoint,
            'payload' => null,
            'status_code' => $response->status(),
        ]);

        return $response->json();
    }

    protected function logRequest(array $data)
    {
        try {
            ApiLog::create($data);
        } catch (\Exception $e) {
        }
    }
}
