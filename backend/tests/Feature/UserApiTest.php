<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\ApiLog;

class UserApiTest extends TestCase
{
    public function test_get_user_endpoint_returns_user_data_successfully()
    {
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

        $response = $this->get('/api/octocat');

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

    public function test_get_user_endpoint_returns_error_for_nonexistent_user()
    {
        Http::fake([
            'api.github.com/users/nonexistentuser123456' => Http::response([
                'message' => 'Not Found',
                'documentation_url' => 'https://docs.github.com/rest/reference/users#get-a-user'
            ], 404)
        ]);

        $response = $this->get('/api/nonexistentuser123456');

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    public function test_get_followings_endpoint_returns_followings_data_successfully()
    {
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

        $response = $this->get('/api/octocat/followings');

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

    public function test_get_followings_endpoint_returns_error_for_nonexistent_user()
    {
        Http::fake([
            'api.github.com/users/nonexistentuser123456/following' => Http::response([
                'message' => 'Not Found',
                'documentation_url' => 'https://docs.github.com/rest/reference/users#list-the-people-a-user-follows'
            ], 404)
        ]);

        $response = $this->get('/api/nonexistentuser123456/followings');

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    public function test_get_user_endpoint_handles_github_api_rate_limit()
    {
        Http::fake([
            'api.github.com/users/testuser' => Http::response([
                'message' => 'API rate limit exceeded',
                'documentation_url' => 'https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting'
            ], 403)
        ]);

        $response = $this->get('/api/testuser');

        $response->assertStatus(403);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    public function test_get_followings_endpoint_handles_github_api_rate_limit()
    {
        Http::fake([
            'api.github.com/users/testuser/following' => Http::response([
                'message' => 'API rate limit exceeded',
                'documentation_url' => 'https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting'
            ], 403)
        ]);

        $response = $this->get('/api/testuser/followings');

        $response->assertStatus(403);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    public function test_get_user_endpoint_handles_server_error()
    {
        Http::fake([
            'api.github.com/users/testuser' => Http::response([
                'message' => 'Server Error'
            ], 500)
        ]);

        $response = $this->get('/api/testuser');

        $response->assertStatus(500);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    public function test_get_followings_endpoint_returns_empty_array_for_user_with_no_followings()
    {
        Http::fake([
            'api.github.com/users/userwithoutfollowings/following' => Http::response([], 200)
        ]);

        $response = $this->get('/api/userwithoutfollowings/followings');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertCount(0, $data);
    }

    public function test_endpoints_handle_special_characters_in_username()
    {
        Http::fake([
            'api.github.com/users/user-with-dash' => Http::response([
                'login' => 'user-with-dash',
                'id' => 123,
                'name' => 'User With Dash'
            ], 200)
        ]);

        $response = $this->get('/api/user-with-dash');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals('user-with-dash', $data['login']);
    }
}
