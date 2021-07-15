<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Collection;
use Tests\TestCase;

class EstoqueControllerTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    protected Collection $data;
    protected Produto $produto;
    private Produto|Collection $estoqueProduto;

    private $fieldSerialized = [
        'id',
        'nome',
        'sku',
        'total_estoque'
    ];

    private function createEstoque(int $qtd = 1, int $qtdProd = 100): void
    {
        $this->estoqueProduto = Produto::factory()
            ->count($qtd)
            ->hasEstoque($qtd, [
                'quantidade' => $qtdProd,
                'acao' => 'Adição'
            ])
            ->create();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->createEstoque();

        $this->produto = Produto::factory()->create();
        $this->data = collect([
            'produto_id' => $this->produto->id,
            'quantidade' => rand(1, 10)
        ]);
    }

    public function testListIndex()
    {
        $this->createEstoque(30);
        $response = $this->json('GET', route('estoque.index'));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->fieldSerialized
                ],
                'links' => []
            ]);

        $response = $this->json('get', route('estoque.index', [
            'page' => 2,
            'per_page' => 10
        ]));
        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');

        $this->assertEquals($response->json('current_page'), 2);
    }

    public function testSuccessAdd()
    {
        $response = $this->json('post', route('estoque.adicao'), $this->data->toArray());
        // dump($this->data->toArray());
        // dd($response->json());
        $response->assertStatus(200);
        $fields = $this->data->keys();
        foreach ($fields as $field) {
            $this->assertEquals(
                $this->data->get($field),
                $response->json($field)
            );
        }
        $this->assertDatabaseHas('estoques', [
            'produto_id' => $response->json('produto_id'),
            'quantidade' => $response->json('quantidade'),
        ]);
    }

    public function testDownEstoqueSuccess()
    {
        $newData = $this->data->merge([
            'produto_id' => $this->estoqueProduto->first()->id,
            'quantidade' => 4
        ]);
        $response = $this->json('put', route('estoque.baixa'), $newData->toArray());
        $response->assertStatus(200);
    }

    public function testRelatorioSuccess()
    {
        $this->createEstoque(3);
        $now = now()->format('Y-m-d');

        $response = $this->json('get', route('estoque.relatorio', [
            'start_date' => $now,
            'end_date' => $now
        ]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            $now => [
                '*' => [
                    'id',
                    'quantidade',
                    'acao',
                    'origem',
                    'updated_at',
                    'nome',
                    'sku'
                ]
            ]
        ]);
        foreach ($response->json() as $prod) {
            $this->assertCount(10, $prod);
        }
    }

    public function testRelatorioErrorStatusCode()
    {
        $response = $this->json('get', route('estoque.relatorio', [
            'start_date' => null,
            'end_date' => null
        ]));
        $response->assertStatus(422);
    }

    public function testQuantidadeEstoque()
    {
        $this->runDatabaseMigrations();
        $this->createEstoque(qtdProd: 80);
        $response = $this->json('get', route('estoque.baixo'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'nome',
                'sku',
                'total_estoque'
            ]
        ]);
    }
}
