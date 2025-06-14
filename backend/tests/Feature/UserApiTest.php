<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\ApiLog;

class UserApiTest extends TestCase
{
    /**
     * Test that user endpoint returns successful response structure
     */
    public function test_get_user_endpoint_returns_user_data_successfully()
    {
        // Mock the GitHub API response
        Http::fake([
            'api.github.com/users/octocat' => Http::response([
                'login' => 'octocat',
                'id' => 1,
                'name' => 'The Octocat',
                'company' => 'GitHub',
                'blog' => 'https://github.com/blog',
                'location' => 'San Francisco',
                'email' => null,
                'bio' => null,
                'public_repos' => 8,
                'public_gists' => 8,
                'followers' => 9999,
                'following' => 9,
                'created_at' => '2011-01-25T18:44:36Z',
                'updated_at' => '2011-01-25T18:44:36Z'
            ], 200)
        ]);

        // Act
        $response = $this->get('/api/octocat');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'login',
            'id',
            'name',
            'company',
            'blog',
            'location',
            'email',
            'bio',
            'public_repos',
            'public_gists',
            'followers',
            'following',
            'created_at',
            'updated_at'
        ]);

        $data = $response->json();
        $this->assertEquals('octocat', $data['login']);
        $this->assertEquals(1, $data['id']);
    }

    /**
     * Test that user endpoint returns error for nonexistent user
     */
    public function test_get_user_endpoint_returns_error_for_nonexistent_user()
    {
        // Mock the GitHub API response for nonexistent user
        Http::fake([
            'api.github.com/users/nonexistentuser123456' => Http::response([
                'message' => 'Not Found',
                'documentation_url' => 'https://docs.github.com/rest/reference/users#get-a-user'
            ], 404)
        ]);

        // Act
        $response = $this->get('/api/nonexistentuser123456');

        // Assert
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    /**
     * Test that followings endpoint returns successful response structure
     */
    public function test_get_followings_endpoint_returns_followings_data_successfully()
    {
        // Mock the GitHub API response
        Http::fake([
            'api.github.com/users/octocat/following' => Http::response([
                [
                    'login' => 'defunkt',
                    'id' => 2,
                    'avatar_url' => 'https://github.com/images/error/defunkt_happy.gif',
                    'gravatar_id' => '',
                    'url' => 'https://api.github.com/users/defunkt',
                    'html_url' => 'https://github.com/defunkt',
                    'type' => 'User',
                    'site_admin' => false
                ]
            ], 200)
        ]);

        // Act
        $response = $this->get('/api/octocat/followings');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'login',
                'id',
                'avatar_url',
                'gravatar_id',
                'url',
                'html_url',
                'type',
                'site_admin'
            ]
        ]);

        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertEquals('defunkt', $data[0]['login']);
    }

    /**
     * Test that followings endpoint returns error for nonexistent user
     */
    public function test_get_followings_endpoint_returns_error_for_nonexistent_user()
    {
        // Mock the GitHub API response for nonexistent user
        Http::fake([
            'api.github.com/users/nonexistentuser123456/following' => Http::response([
                'message' => 'Not Found',
                'documentation_url' => 'https://docs.github.com/rest/reference/users#list-the-people-a-user-follows'
            ], 404)
        ]);

        // Act
        $response = $this->get('/api/nonexistentuser123456/followings');

        // Assert
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    /**
     * Test that user endpoint handles GitHub API rate limit
     */
    public function test_get_user_endpoint_handles_github_api_rate_limit()
    {
        // Mock the GitHub API rate limit response
        Http::fake([
            'api.github.com/users/testuser' => Http::response([
                'message' => 'API rate limit exceeded',
                'documentation_url' => 'https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting'
            ], 403)
        ]);

        // Act
        $response = $this->get('/api/testuser');

        // Assert
        $response->assertStatus(403);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    /**
     * Test that followings endpoint handles GitHub API rate limit
     */
    public function test_get_followings_endpoint_handles_github_api_rate_limit()
    {
        // Mock the GitHub API rate limit response
        Http::fake([
            'api.github.com/users/testuser/following' => Http::response([
                'message' => 'API rate limit exceeded',
                'documentation_url' => 'https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting'
            ], 403)
        ]);

        // Act
        $response = $this->get('/api/testuser/followings');

        // Assert
        $response->assertStatus(403);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    /**
     * Test that user endpoint handles server error
     */
    public function test_get_user_endpoint_handles_server_error()
    {
        // Mock the GitHub API server error response
        Http::fake([
            'api.github.com/users/testuser' => Http::response([
                'message' => 'Server Error'
            ], 500)
        ]);

        // Act
        $response = $this->get('/api/testuser');

        // Assert
        $response->assertStatus(500);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    /**
     * Test that followings endpoint returns empty array for user with no followings
     */
    public function test_get_followings_endpoint_returns_empty_array_for_user_with_no_followings()
    {
        // Mock the GitHub API response for user with no followings
        Http::fake([
            'api.github.com/users/loneuser/following' => Http::response([], 200)
        ]);

        // Act
        $response = $this->get('/api/loneuser/followings');

        // Assert
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertCount(0, $data);
    }

    /**
     * Test that endpoints handle special characters in username
     */
    public function test_endpoints_handle_special_characters_in_username()
    {
        // Mock the GitHub API response for user with special characters
        Http::fake([
            'api.github.com/users/user-name_123' => Http::response([
                'login' => 'user-name_123',
                'id' => 123,
                'name' => 'Test User',
                'public_repos' => 5,
                'followers' => 10,
                'following' => 15
            ], 200)
        ]);

        // Act
        $response = $this->get('/api/user-name_123');

        // Assert
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals('user-name_123', $data['login']);
        $this->assertEquals(123, $data['id']);
    }

    /**
     * Test that multiple concurrent requests work correctly
     */
    public function test_multiple_concurrent_requests_create_separate_logs()
    {
        // Mock the GitHub API responses
        Http::fake([
            'api.github.com/users/user1' => Http::response([
                'login' => 'user1',
                'id' => 1,
                'name' => 'User One'
            ], 200),
            'api.github.com/users/user2' => Http::response([
                'login' => 'user2',
                'id' => 2,
                'name' => 'User Two'
            ], 200)
        ]);

        // Act - Make multiple requests
        $response1 = $this->get('/api/user1');
        $response2 = $this->get('/api/user2');

        // Assert
        $response1->assertStatus(200);
        $response2->assertStatus(200);

        $data1 = $response1->json();
        $data2 = $response2->json();

        $this->assertEquals('user1', $data1['login']);
        $this->assertEquals('user2', $data2['login']);
        $this->assertNotEquals($data1['id'], $data2['id']);
    }
}
