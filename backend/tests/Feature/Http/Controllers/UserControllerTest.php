<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected User $usuario;
    protected Generator $fake;
    protected Collection $data;

    private $fieldSerialized = [
        'access_token',
        'token_type',
        'expires_in',
        'name',
    ];

    private array $routesProtected = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->fake = Factory::create(
            \Config::get('app.faker_locale')
        );

        $this->data = collect([
            'email' => $this->fake->safeEmail(),
            'password' => $this->fake->password(maxLength:8),
            'name' => $this->fake->name()
        ]);

        $this->usuario = User::factory()->create([
            'email' => $this->data->get('email'),
            'password' => \Hash::make($this->data->get('password')),
            'name' => $this->data->get('name')
        ]);
        $this->routesProtected = [
            [
                'method' => 'post',
                'route' => route('auth.logout')
            ],
            [
                'method' => 'get',
                'route' => route('produto.index')
            ],
            [
                'method' => 'get',
                'route' => route('estoque.index')
            ]
        ];
    }

    public function testSuccessLogin()
    {
        $response = $this->json('post',route('auth.login'),$this->data->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure($this->fieldSerialized);
    }

    public function testLogoutSuccess()
    {
        $response = $this->json('post',route('auth.login'),$this->data->toArray());
        $token = $response->json('access_token');
        $response = $this->json(
            'post',
            route('auth.logout'),
            headers: ['Authorization' => "Bearer {$token}"]
        );
        $response->assertStatus(204);
    }

    public function testUnauthorizedAuthRoute()
    {
        foreach($this->routesProtected as $routes){
            $response = $this->json($routes['method'],$routes['route']);
            $response->assertStatus(401);
        }
    }
}
