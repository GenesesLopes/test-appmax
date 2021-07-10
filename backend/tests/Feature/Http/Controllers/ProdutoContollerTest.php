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
            'sku' => md5(uniqid()),
            'nome' => 'Nome qualquer'
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

    public function testUpdateSuccess()
    {
        $response = $this->json('put',$this->routeUpdate,$this->data->toArray());
        $response->assertStatus(200);
        $this->assertEquals($this->data->get('sku'),$response->json('sku'));
        $this->assertDatabaseCount('produtos',1);
    }

    public function testStoreFail()
    {
        $response = $this->json('post',$this->routeStore,[]);
        $response->assertStatus(422);

        $data = $this->data->merge([
            'sku' => $this->produto->sku
        ])->toArray();

        $response = $this->json('post',$this->routeStore,$data);
        $response->assertStatus(422);
    }

    public function testStoreUpdate()
    {
        $dataFake = Produto::factory()->create();
        $data = $this->data->merge([
            'sku' => $dataFake->sku
        ])->toArray();
        $response = $this->json('put',$this->routeUpdate,$data);
        $response->assertStatus(422);
    }

    public function testDeleteSuccess()
    {
        $response = $this->json('delete',route('produto.destroy',['id' => $this->produto->id]));
        $response->assertStatus(204);

    }

    public function testDeleteFail()
    {
        $response = $this->json('delete',route('produto.destroy',['id' => 10]));
        $response->assertStatus(422);
    }




    
}
