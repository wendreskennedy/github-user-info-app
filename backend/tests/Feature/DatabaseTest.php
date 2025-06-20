<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ApiLog;

class DatabaseTest extends TestCase
{
    public function test_api_log_model_structure_is_correct()
    {
        $apiLog = new ApiLog();

        $expectedFillable = ['method', 'endpoint', 'payload', 'status_code'];
        $this->assertEquals($expectedFillable, $apiLog->getFillable());

        $this->assertEquals('api_logs', $apiLog->getTable());

        $this->assertEquals('id', $apiLog->getKeyName());

        $this->assertTrue($apiLog->usesTimestamps());
    }

    public function test_api_log_data_validation()
    {
        $apiLog = new ApiLog();

        $validMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        foreach ($validMethods as $method) {
            $apiLog->method = $method;
            $this->assertEquals($method, $apiLog->method);
        }

        $validStatusCodes = [200, 201, 400, 401, 404, 500];
        foreach ($validStatusCodes as $statusCode) {
            $apiLog->status_code = $statusCode;
            $this->assertEquals($statusCode, $apiLog->status_code);
        }
    }

    public function test_api_log_payload_handling()
    {
        $apiLog = new ApiLog();

        $jsonPayload = json_encode(['user' => 'testuser', 'action' => 'fetch']);
        $apiLog->payload = $jsonPayload;
        $this->assertEquals($jsonPayload, $apiLog->payload);

        $largePayload = str_repeat('a', 1000);
        $apiLog->payload = $largePayload;
        $this->assertEquals($largePayload, $apiLog->payload);

        $apiLog->payload = null;
        $this->assertNull($apiLog->payload);
    }

    public function test_api_log_endpoint_validation()
    {
        $apiLog = new ApiLog();

        $validEndpoints = [
            '/api/user/testuser',
            '/api/user/testuser/followings',
            '/api/health',
            '/api/status'
        ];

        foreach ($validEndpoints as $endpoint) {
            $apiLog->endpoint = $endpoint;
            $this->assertEquals($endpoint, $apiLog->endpoint);
        }
    }

    public function test_api_log_mass_assignment()
    {
        $data = [
            'method' => 'GET',
            'endpoint' => '/api/user/testuser',
            'payload' => json_encode(['test' => 'data']),
            'status_code' => 200
        ];

        $apiLog = new ApiLog($data);

        $this->assertEquals('GET', $apiLog->method);
        $this->assertEquals('/api/user/testuser', $apiLog->endpoint);
        $this->assertEquals(json_encode(['test' => 'data']), $apiLog->payload);
        $this->assertEquals(200, $apiLog->status_code);
    }

    public function test_api_log_model_casts()
    {
        $apiLog = new ApiLog();

        $apiLog->status_code = '200';
        $this->assertEquals('200', $apiLog->status_code);

        $apiLog->status_code = 200;
        $this->assertEquals(200, $apiLog->status_code);
    }

    public function test_api_log_model_relationships()
    {
        $apiLog = new ApiLog();

        $this->assertInstanceOf(ApiLog::class, $apiLog);
        $this->assertTrue(method_exists($apiLog, 'save'));
        $this->assertTrue(method_exists($apiLog, 'delete'));
        $this->assertTrue(method_exists($apiLog, 'update'));
    }

    public function test_api_log_serialization()
    {
        $data = [
            'method' => 'POST',
            'endpoint' => '/api/user/testuser/followings',
            'payload' => json_encode(['page' => 1, 'per_page' => 30]),
            'status_code' => 201
        ];

        $apiLog = new ApiLog($data);

        $array = $apiLog->toArray();
        $this->assertIsArray($array);
        $this->assertArrayHasKey('method', $array);
        $this->assertArrayHasKey('endpoint', $array);
        $this->assertArrayHasKey('payload', $array);
        $this->assertArrayHasKey('status_code', $array);

        $json = $apiLog->toJson();
        $this->assertIsString($json);
        $decodedJson = json_decode($json, true);
        $this->assertIsArray($decodedJson);
    }

    public function test_api_log_validation_scenarios()
    {
        $successLog = new ApiLog([
            'method' => 'GET',
            'endpoint' => '/api/user/octocat',
            'payload' => null,
            'status_code' => 200
        ]);

        $this->assertEquals('GET', $successLog->method);
        $this->assertEquals(200, $successLog->status_code);

        $errorLog = new ApiLog([
            'method' => 'GET',
            'endpoint' => '/api/user/nonexistentuser',
            'payload' => null,
            'status_code' => 404
        ]);

        $this->assertEquals('GET', $errorLog->method);
        $this->assertEquals(404, $errorLog->status_code);
    }
}
