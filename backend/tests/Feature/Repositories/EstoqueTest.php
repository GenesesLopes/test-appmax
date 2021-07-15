<?php

namespace Tests\Feature\Repositories;

use App\Models\Estoque;
use App\Models\Produto;
use App\Repositories\Eloquent\EstoqueRepository;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection as SupportCollection;

use Tests\TestCase;
use Tests\Traits\TestArrayIntersect;

class EstoqueTest extends TestCase
{
    use DatabaseMigrations, TestArrayIntersect;

    private Generator $fakeData;
    private Estoque|Collection $estoque;
    private SupportCollection $data;
    private EstoqueRepository $estoqueRepository;
    private Produto $produto;


    private function createEstoque(
        array $dataEstoque = null
    ): void {
        if($dataEstoque == null)
            $dataEstoque = $this->data->toArray();
        $this->estoque = Estoque::factory()->create($dataEstoque);
    }


    protected function setUp(): void
    {
        parent::setUp();
        // $this->mockInterface = Mockery::mock(IMovimentacao::class);
        // $resClass = new \ReflectionClass(EstoqueRepository::class);
        // $newInstace = $resClass->newInstance(new MovimentacaoRepository);
        $this->fakeData = Factory::create(
            \Config::get('app.faker_locale')
        );
        $this->estoqueRepository = new EstoqueRepository;
        $this->produto = Produto::factory()->create();
        $fakeEstoque = Estoque::factory()->makeOne()->toArray();
        $this->data = collect($fakeEstoque)->merge([
            'produto_id' => $this->produto->id
        ]);
        $this->createEstoque();
        
    }

    public function testFindProdutoEstoque()
    {
        /** @var Estoque */
        $firstEstoque = $this->estoque->first();
        $response = $this->estoqueRepository->findProduto($firstEstoque->id);
        $this->assertArrayIntersect(
            $firstEstoque->getAttributes(),
            $response->getAttributes(),
            true
        );
    }

    public function testPersistenceSuccess()
    {
        $estoque = new Estoque($this->data->toArray());
        $response = $this->estoqueRepository->persistence($estoque);
        $this->assertInstanceOf(Estoque::class,$response);
        // dump($response->getAttributes(),$this->data->toArray());
        $this->assertArrayIntersect(
            $this->data->toArray(),
            $response->getAttributes()
        );
    }

    public function testCountQuantidade()
    {
        $this->estoque->delete();
        $data = [
            [
                'quantidade' => 2,
                'acao' => 'Adição'
            ],
            [
                'quantidade' => 3,
                'acao' => 'Adição'
            ],
            [
                'quantidade' => 1,
                'acao' => 'Remoção'
            ],
        ];
        foreach($data as $value){
            $newData = $this->data->merge($value)->toArray();
            $this->createEstoque($newData);
        }
        $this->assertEquals(
            4,
            $this->estoqueRepository->countQuantidadeProduto($this->produto->id)
        );
    }

    public function testCountQuantidadeBaixa()
    {
        $this->estoque->delete();
        $response = $this->estoqueRepository->QuantidadeEstoqueBaixa();
        $this->assertCount(0,$response->toArray());
        $data = [
            [
                'quantidade' => 2,
                'acao' => 'Adição'
            ],
            [
                'quantidade' => 3,
                'acao' => 'Adição'
            ],
            [
                'quantidade' => 1,
                'acao' => 'Remoção'
            ],
        ];
        foreach($data as $value){
            $newData = $this->data->merge($value)->toArray();
            $this->createEstoque($newData);
        }
        $response = $this->estoqueRepository->QuantidadeEstoqueBaixa();
        $adicao = $response->filter(fn($data) => $data->acao == 'Adição')->first();
        $remocao = $response->filter(fn($data) => $data->acao == 'Remoção')->first();
        $this->assertEquals(5,$adicao->total_somado);
        $this->assertEquals(1,$remocao->total_somado);
    }

    public function testRelatorio()
    {
        $dataSearch = [
            'start_date' => now()->subDay()->format('Y-m-d H:i:s'),
            'end_date' => now()->format('Y-m-d H:i:s')
        ];
        $data = [
            [
                'quantidade' => 2,
                'acao' => 'Adição'
            ],
            [
                'quantidade' => 3,
                'acao' => 'Adição'
            ],
            [
                'quantidade' => 1,
                'acao' => 'Remoção'
            ],
        ];
        foreach($data as $value){
            $newData = $this->data->merge($value)->toArray();
            $this->createEstoque($newData);
        }
        $response = $this->estoqueRepository->relatorioMovimentos($dataSearch);
        $fields = [
            'id',
            'quantidade',
            'acao',
            'origem',
            'updated_at',
            'nome',
            'sku'
        ];
        foreach($response->toArray() as $responseData){
            $this->assertIsObject($responseData);
            $keysObject = array_keys(get_object_vars($responseData));
            $this->assertArrayIntersect($fields,$keysObject);
        }
    }
}
