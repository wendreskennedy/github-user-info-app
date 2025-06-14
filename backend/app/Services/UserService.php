<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ApiLog;

class UserService
{
    protected $baseUrl = 'https://api.github.com/users/';

    public function getUser(string $username): ?array
    {
        $endpoint = $this->baseUrl . $username;
        $response = Http::get($endpoint);

        $this->logRequest([
            'method' => 'GET',
            'endpoint' => $endpoint,
            'payload' => null,
            'status_code' => $response->status(),
        ]);

        if ($response->failed()) {
            throw new \Exception('Erro ao obter usuario.', $response->status());
        }

        return $response->json();
    }

    public function getFollowings(string $username): ?array
    {
        $endpoint = $this->baseUrl . $username . '/following';
        $response = Http::get($endpoint);

        $this->logRequest([
            'method' => 'GET',
            'endpoint' => $endpoint,
            'payload' => null,
            'status_code' => $response->status(),
        ]);

        if ($response->failed()) {
            throw new \Exception('Erro ao obter followings.', $response->status());
        }

        return $response->json();
    }

    protected function logRequest(array $data)
    {
        try {
            ApiLog::create($data);
        } catch (\Exception $e) {
            // Silently fail if database is not available (e.g., in testing)
            // In production, you might want to log this to a file or monitoring service
        }
    }
}
