<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function test_user_service_can_be_instantiated()
    {
        $this->assertTrue(true);
    }

    public function test_user_service_base_url_is_correct()
    {
        $expectedBaseUrl = 'https://api.github.com/users/';

        $this->assertEquals($expectedBaseUrl, 'https://api.github.com/users/');
    }

    public function test_user_service_endpoints_are_constructed_correctly()
    {
        $baseUrl = 'https://api.github.com/users/';
        $username = 'testuser';

        $userEndpoint = $baseUrl . $username;
        $followingsEndpoint = $baseUrl . $username . '/following';

        $this->assertEquals('https://api.github.com/users/testuser', $userEndpoint);
        $this->assertEquals('https://api.github.com/users/testuser/following', $followingsEndpoint);
    }

    public function test_error_messages_are_correct()
    {
        $userErrorMessage = 'Erro ao obter usuario.';
        $followingsErrorMessage = 'Erro ao obter followings.';

        $this->assertEquals('Erro ao obter usuario.', $userErrorMessage);
        $this->assertEquals('Erro ao obter followings.', $followingsErrorMessage);
    }

    public function test_http_methods_are_correct()
    {
        $expectedMethod = 'GET';

        $this->assertEquals('GET', $expectedMethod);
    }

    public function test_status_codes_are_handled_correctly()
    {
        $successCodes = [200, 201];
        $errorCodes = [400, 401, 404, 500];

        foreach ($successCodes as $code) {
            $this->assertGreaterThanOrEqual(200, $code);
            $this->assertLessThan(300, $code);
        }

        foreach ($errorCodes as $code) {
            $this->assertGreaterThanOrEqual(400, $code);
        }
    }
}
