<?php

namespace Tests\Unit;

use App\Http\Controllers\UserController;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected UserController $controller;
    protected $userServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userServiceMock = Mockery::mock(UserService::class);
        $this->controller = new UserController($this->userServiceMock);
    }

    public function test_get_user_returns_json_response_with_user_data()
    {
        $username = 'testuser';
        $expectedUserData = [
            'login' => 'testuser',
            'id' => 12345,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'public_repos' => 10,
            'followers' => 5,
            'following' => 3
        ];

        $this->userServiceMock
            ->shouldReceive('getUser')
            ->once()
            ->with($username)
            ->andReturn($expectedUserData);

        $response = $this->controller->getUser($username);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedUserData, $response->getData(true));
    }

    public function test_get_user_returns_error_response_when_service_throws_exception()
    {
        $username = 'nonexistentuser';
        $exceptionMessage = 'Erro ao obter usuario.';
        $exceptionCode = 404;

        $this->userServiceMock
            ->shouldReceive('getUser')
            ->once()
            ->with($username)
            ->andThrow(new \Exception($exceptionMessage, $exceptionCode));

        $response = $this->controller->getUser($username);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($exceptionCode, $response->getStatusCode());
        $this->assertEquals(['error' => 'Erro ao obter usuario.'], $response->getData(true));
    }

    public function test_get_user_handles_exception_with_zero_code()
    {
        $username = 'testuser';
        $exceptionMessage = 'Erro genérico';
        $exceptionCode = 0;

        $this->userServiceMock
            ->shouldReceive('getUser')
            ->once()
            ->with($username)
            ->andThrow(new \Exception($exceptionMessage, $exceptionCode));

        $response = $this->controller->getUser($username);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['error' => 'Erro ao obter usuario.'], $response->getData(true));
    }

    public function test_get_followings_returns_json_response_with_followings_data()
    {
        $username = 'testuser';
        $expectedFollowingsData = [
            [
                'login' => 'follower1',
                'id' => 11111,
                'avatar_url' => 'https://avatars.githubusercontent.com/u/11111?v=4'
            ],
            [
                'login' => 'follower2',
                'id' => 22222,
                'avatar_url' => 'https://avatars.githubusercontent.com/u/22222?v=4'
            ]
        ];

        $this->userServiceMock
            ->shouldReceive('getFollowings')
            ->once()
            ->with($username)
            ->andReturn($expectedFollowingsData);

        $response = $this->controller->getFollowings($username);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedFollowingsData, $response->getData(true));
    }

    public function test_get_followings_returns_error_response_when_service_throws_exception()
    {
        $username = 'nonexistentuser';
        $exceptionMessage = 'Erro ao obter followings.';
        $exceptionCode = 404;

        $this->userServiceMock
            ->shouldReceive('getFollowings')
            ->once()
            ->with($username)
            ->andThrow(new \Exception($exceptionMessage, $exceptionCode));

        $response = $this->controller->getFollowings($username);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($exceptionCode, $response->getStatusCode());
        $this->assertEquals(['error' => 'Erro ao obter followings do usuario.'], $response->getData(true));
    }

    public function test_get_followings_returns_empty_array_when_user_has_no_followings()
    {
        $username = 'userwithnofollowings';
        $expectedFollowingsData = [];

        $this->userServiceMock
            ->shouldReceive('getFollowings')
            ->once()
            ->with($username)
            ->andReturn($expectedFollowingsData);

        $response = $this->controller->getFollowings($username);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedFollowingsData, $response->getData(true));
    }

    public function test_get_user_handles_server_error_exception()
    {
        $username = 'testuser';
        $exceptionMessage = 'Internal Server Error';
        $exceptionCode = 500;

        $this->userServiceMock
            ->shouldReceive('getUser')
            ->once()
            ->with($username)
            ->andThrow(new \Exception($exceptionMessage, $exceptionCode));

        $response = $this->controller->getUser($username);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['error' => 'Erro ao obter usuario.'], $response->getData(true));
    }

    public function test_get_followings_handles_server_error_exception()
    {
        $username = 'testuser';
        $exceptionMessage = 'Internal Server Error';
        $exceptionCode = 500;

        $this->userServiceMock
            ->shouldReceive('getFollowings')
            ->once()
            ->with($username)
            ->andThrow(new \Exception($exceptionMessage, $exceptionCode));

        $response = $this->controller->getFollowings($username);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['error' => 'Erro ao obter followings do usuario.'], $response->getData(true));
    }

    public function test_get_user_handles_rate_limit_exception()
    {
        $username = 'testuser';
        $exceptionMessage = 'API rate limit exceeded';
        $exceptionCode = 403;

        $this->userServiceMock
            ->shouldReceive('getUser')
            ->once()
            ->with($username)
            ->andThrow(new \Exception($exceptionMessage, $exceptionCode));

        $response = $this->controller->getUser($username);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(['error' => 'Erro ao obter usuario.'], $response->getData(true));
    }

    public function test_get_followings_handles_rate_limit_exception()
    {
        $username = 'testuser';
        $exceptionMessage = 'API rate limit exceeded';
        $exceptionCode = 403;

        $this->userServiceMock
            ->shouldReceive('getFollowings')
            ->once()
            ->with($username)
            ->andThrow(new \Exception($exceptionMessage, $exceptionCode));

        $response = $this->controller->getFollowings($username);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(['error' => 'Erro ao obter followings do usuario.'], $response->getData(true));
    }

    public function test_controller_constructor_sets_service_dependency()
    {
        $userService = Mockery::mock(UserService::class);
        $controller = new UserController($userService);

        $this->assertInstanceOf(UserController::class, $controller);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
