<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Cache;

class UserService
{
    protected $baseUrl = 'https://api.github.com/users/';
    protected $tempStatusCode;

    public function getUser(string $username): ?array
    {
        $endpoint = $this->baseUrl . $username;

        $userDataResponse = Cache::remember("github_user_{$username}", now()->addMinutes(30), function () use ($endpoint) {
            $response = Http::get($endpoint);

            if ($response->failed()) {
                throw new \Exception('Erro ao obter usuario.', $response->status());
            }

            $this->tempStatusCode = $response->status();

            return $response->json();
        });

        $statusCode = $this->tempStatusCode ?? 200;

        $this->logRequest([
            'method' => 'GET',
            'endpoint' => $endpoint,
            'payload' => null,
            'status_code' => $statusCode,
        ]);

        return $userDataResponse;
    }

    public function getFollowings(string $username): ?array
    {
        $endpoint = $this->baseUrl . $username . '/following';

        $followingsData = Cache::remember("github_followings_{$username}", now()->addMinutes(30), function () use ($endpoint) {
            $response = Http::get($endpoint);

            if ($response->failed()) {
                throw new \Exception('Erro ao obter followings.', $response->status());
            }

            $this->tempStatusCode = $response->status();

            return $response->json();
        });

        $statusCode = $this->tempStatusCode ?? 200;
        $this->logRequest([
            'method' => 'GET',
            'endpoint' => $endpoint,
            'payload' => null,
            'status_code' => $statusCode,
        ]);

        return $followingsData;
    }

    protected function logRequest(array $data)
    {
        try {
            ApiLog::create($data);
        } catch (\Exception $e) {
        }
    }
}
