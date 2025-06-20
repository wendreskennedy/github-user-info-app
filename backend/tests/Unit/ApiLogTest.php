<?php

namespace Tests\Unit;

use App\Models\ApiLog;
use PHPUnit\Framework\TestCase;

class ApiLogTest extends TestCase
{
    public function test_api_log_can_be_instantiated()
    {
        $apiLog = new ApiLog();

        $this->assertInstanceOf(ApiLog::class, $apiLog);
    }

    public function test_api_log_has_correct_table_name()
    {
        $apiLog = new ApiLog();

        $this->assertEquals('api_logs', $apiLog->getTable());
    }

    public function test_api_log_has_correct_primary_key()
    {
        $apiLog = new ApiLog();

        $this->assertEquals('id', $apiLog->getKeyName());
    }

    public function test_api_log_has_timestamps_enabled()
    {
        $apiLog = new ApiLog();

        $this->assertTrue($apiLog->timestamps);
    }

    public function test_api_log_fillable_attributes()
    {
        $apiLog = new ApiLog();
        $expectedFillable = [
            'method',
            'endpoint',
            'payload',
            'status_code'
        ];

        $this->assertEquals($expectedFillable, $apiLog->getFillable());
    }

    public function test_api_log_attributes_can_be_set()
    {
        $apiLog = new ApiLog();
        $testData = [
            'method' => 'GET',
            'endpoint' => 'https://api.github.com/users/testuser',
            'payload' => json_encode(['key' => 'value']),
            'status_code' => 200
        ];

        $apiLog->fill($testData);

        $this->assertEquals('GET', $apiLog->method);
        $this->assertEquals('https://api.github.com/users/testuser', $apiLog->endpoint);
        $this->assertEquals(json_encode(['key' => 'value']), $apiLog->payload);
        $this->assertEquals(200, $apiLog->status_code);
    }

    public function test_api_log_handles_null_payload()
    {
        $apiLog = new ApiLog();
        $testData = [
            'method' => 'GET',
            'endpoint' => 'https://api.github.com/users/testuser',
            'payload' => null,
            'status_code' => 200
        ];

        $apiLog->fill($testData);

        $this->assertNull($apiLog->payload);
    }

    public function test_api_log_handles_different_http_methods()
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

        foreach ($methods as $method) {
            $apiLog = new ApiLog();
            $apiLog->fill(['method' => $method]);

            $this->assertEquals($method, $apiLog->method);
        }
    }

    public function test_api_log_handles_different_status_codes()
    {
        $statusCodes = [200, 201, 400, 401, 404, 500];

        foreach ($statusCodes as $statusCode) {
            $apiLog = new ApiLog();
            $apiLog->fill(['status_code' => $statusCode]);

            $this->assertEquals($statusCode, $apiLog->status_code);
        }
    }
}
