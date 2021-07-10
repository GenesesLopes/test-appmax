<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Produto;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProdutoContollerTest extends TestCase
{
    use DatabaseMigrations;
    
    private Produto $produto;
    private string $routeStore;
    private string $routeUpdate;
    private Collection $data;

    private $fieldSerialized = [
        'id',
        'sku',
        'deleted_at',
        'created_at',
        'updated_at'
    ];


    protected function setUp(): void
    {
        parent::setUp();
        $this->produto = Produto::factory()->create();
        $this->routeStore = route('produto.store');
        $this->routeUpdate = route('produto.update', ['id' => $this->produto->id]);
        $this->data = collect([
            'sku' => md5(uniqid())
        ]);
    }

    public function testListIndex()
    {
        $response = $this->json('GET',$this->routeStore);
        $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => $this->fieldSerialized
            ],
            'links' => []
        ]);
    }

    public function testShow()
    {
        $response = $this->json('get',route('produto.show',['id' => $this->produto->id]));
        $response->assertStatus(200)
            ->assertJsonStructure($this->fieldSerialized);
        $this->assertEquals($this->produto->sku,$response->json('sku'));
    }

    public function testStoreSuccess()
    {
        $response = $this->json('post',$this->routeStore,$this->data->toArray());
        $response->assertStatus(201);
        $this->assertEquals($this->data->get('sku'),$response->json('sku'));
        $this->assertDatabaseCount('produtos',2);
    }

    public function testStoreUpdate()
    {
        $response = $this->json('put',$this->routeUpdate,$this->data->toArray());
        $response->assertStatus(200);
        $this->assertEquals($this->data->get('sku'),$response->json('sku'));
        $this->assertDatabaseCount('produtos',1);
    }



    
}
