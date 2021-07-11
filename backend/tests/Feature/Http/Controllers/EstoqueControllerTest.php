<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class EstoqueControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected Collection $data;
    protected Produto $produto;
    private Estoque|Collection $estoque;

    private $fieldSerialized = [
        'id',
        'produto_id',
        'quantidade',
        'created_at',
        'updated_at'
    ];

    private function createEstoque(int $qtd = 1): void
    {
        $this->estoque = Estoque::factory()
            ->count($qtd)
            ->for(
                Produto::factory()->create()
            )->create([
                'quantidade' => 100
            ]);
    }
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->createEstoque();
        $this->produto = Produto::factory()->create();
        $this->data = collect([
            'produto_id' => $this->produto->id,
            'quantidade' => rand(1,10)
        ]);
    }

    public function testListIndex()
    {
        $this->createEstoque(30);
        $response = $this->json('GET',route('estoque.index'));
        $response->assertStatus(200)
        ->assertJsonCount(15,'data')
        ->assertJsonStructure([
            'data' => [
                '*' => $this->fieldSerialized
            ],
            'links' => []
        ]);

        $response = $this->json('get',route('estoque.index',[
            'page' => 2,
            'per_page' => 10
        ]));
        $response->assertStatus(200)
            ->assertJsonCount(10,'data');
        
        $this->assertEquals($response->json('current_page'),2);
    }

    public function testSuccessAdd()
    {
        $response = $this->json('post',route('estoque.adicao'),$this->data->toArray());
        $response->assertStatus(200);
        $fields = $this->data->keys();
        foreach($fields as $field){
            $this->assertEquals(
                $this->data->get($field),
                $response->json($field)
            );
        }
        $this->assertDatabaseHas('estoques',[
            'produto_id' => $response->json('produto_id'),
            'quantidade' => $response->json('quantidade'),
        ]);
    }

    public function testDownEstoqueSuccess()
    {
        $newData = $this->data->merge([
            'produto_id' => $this->estoque->first()->produto_id,
            'quantidade' => 4
        ]);
        $response = $this->json('put',route('estoque.baixa'),$newData->toArray());
        $response->assertStatus(200);
    }
}
