<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\Tests\ExceptionTest;
use App\Models\Estoque;
use App\Models\Movimentacao;
use App\Models\Produto;
use App\Repositories\Contracts\IMovimentacao;
use App\Repositories\Eloquent\EstoqueRepository;
use App\Repositories\Eloquent\MovimentacaoRepository;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection as SupportCollection;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Traits\TestArrayIntersect;

class EstoqueTest extends TestCase
{
    use DatabaseMigrations, TestArrayIntersect;

    private Generator $fakeData;
    private Estoque|Collection $estoque;
    private MockInterface|LegacyMockInterface|IMovimentacao $mockInterface;
    private SupportCollection $data;
    private EstoqueRepository $estoqueRepository;
    private Produto $produto;


    private function createEstoque(int $qtd = 1, int $qtdProd = 100): void
    {
        $this->estoque = Estoque::factory()
            ->count($qtd)
            ->for(
                Produto::factory()->create()
            )->create([
                'quantidade' => $qtdProd
            ]);
    }

    private function getQuantidade(): int
    {
        do {
            $quantidade = $this->fakeData->numberBetween(-2, 4);
        } while ($quantidade == 0);
        return $quantidade;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockInterface = Mockery::mock(IMovimentacao::class);
        $resClass = new \ReflectionClass(EstoqueRepository::class);
        $newInstace = $resClass->newInstance(new MovimentacaoRepository);
        $this->estoqueRepository = $newInstace;
        $this->createEstoque();
        $this->fakeData = Factory::create(
            \Config::get('app.faker_locale')
        );
        $quantidade = $this->fakeData->numberBetween(1, 4);
        $this->produto = Produto::factory()->create();
        $this->data = collect([
            'produto_id' => $this->produto->id,
            'quantidade' => $quantidade,
            'acao' => $quantidade < 0 ? 'Remocao' : 'Adicao',
            'origem' => rand(0, 5) % 2 == 0 ? 'sistema' : 'api'
        ]);
    }

    public function testFindProdutoEstoque()
    {
        $firstEstoque = $this->estoque->first();
        $response = $this->estoqueRepository->findProduto($firstEstoque->getAttributes());
        $this->assertArrayIntersect(
            $firstEstoque->getAttributes(),
            $response->getAttributes(),
            true
        );
    }

    public function testFindOrCreateProdutoEstoque()
    {
        $data = [
            'produto_id' => Produto::factory()->create()->id,
            'quantidade' => $this->fakeData->randomNumber(2)
        ];

        $response = $this->estoqueRepository->findProduto($data);
        $this->assertNull($response);
        $data['produto_id'] = $this->estoque->first()->produto_id;
        $response = $this->estoqueRepository->findProduto($data);
        
        $this->assertArrayIntersect(
            $data,
            $response->getAttributes()
        );
    }

    public function testSuccessAdd()
    {
        $data = [3, 4, 8, 14];
        foreach ($data as $dataValue) {
            $oldEstoque = Estoque::first();

            $newData = $this->data->merge([
                'quantidade' => $dataValue,
                'acao' => 'Adicao',
                'produto_id' => $oldEstoque->produto_id
            ])->toArray();

            $response = $this->estoqueRepository->add($newData);
            $this->assertEquals($oldEstoque->quantidade + $dataValue, $response->quantidade);
        }
    }

    public function testErroTransactionAddMovimentacao()
    {
       $mock = $this->mockInterface->shouldReceive('add')
            ->withAnyArgs()
            ->andThrow(new ExceptionTest('Erro Provocado para Teste'))
            ->getMock();
        $this->estoqueRepository = new EstoqueRepository($mock);
        
        $newData = $this->data->merge([
            'quantidade' => 2,
            'acao' => 'Adicao',
            'produto_id' => $this->estoque->first()->produto_id
        ])->toArray();
        try {
            $this->estoqueRepository->add($newData);
        } catch (ExceptionTest $e) {
            $estoqueAtual = Estoque::first()->quantidade;
            $this->assertEquals($estoqueAtual,$this->estoque->first()->quantidade);
        }

        $this->assertTrue(isset($e));
    }

    public function testSuccessRemove()
    {
        $data = [3, 4, 8, 14];
        foreach ($data as $dataValue) {
            $oldEstoque = Estoque::first();

            $newData = $this->data->merge([
                'quantidade' => $dataValue,
                'acao' => 'Adicao',
                'produto_id' => $oldEstoque->produto_id
            ])->toArray();

            $response = $this->estoqueRepository->remove($newData);
            $this->assertEquals($oldEstoque->quantidade - $dataValue, $response->quantidade);
        }
    }

    public function testErroTransactionRemoveMovimentacao()
    {
       $mock = $this->mockInterface->shouldReceive('add')
            ->withAnyArgs()
            ->andThrow(new ExceptionTest('Erro Provocado para Teste'))
            ->getMock();
        $this->estoqueRepository = new EstoqueRepository($mock);
        
        $newData = $this->data->merge([
            'quantidade' => 2,
            'acao' => 'Adicao',
            'produto_id' => $this->estoque->first()->produto_id
        ])->toArray();
        try {
            $this->estoqueRepository->add($newData);
        } catch (ExceptionTest $e) {
            $estoqueAtual = Estoque::first()->quantidade;
            $this->assertEquals($estoqueAtual,$this->estoque->first()->quantidade);
        }

        $this->assertTrue(isset($e));
    }

    public function testQuantidadeEstoque()
    {
        $this->runDatabaseMigrations();
        $this->createEstoque(1, 90);
        $response = $this->estoqueRepository->QuantidadeEstoque();
        $this->assertCount(1,$response->all());
        $this->assertEquals(90,$response->first()->total_estoque);
    }
}
