<?php

namespace Tests\Unit\Services;

use App\Exceptions\Utils\Information;
use App\Exceptions\Utils\InsufficientQuantity;
use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use App\Services\EstoqueServices;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use stdClass;
use Tests\TestCase;
use Tests\Traits\TestArrayIntersect;

class EstoqueServicesUnitTest extends TestCase
{
    use TestArrayIntersect;

    protected IEstoque $estoqueRepository;
    protected EstoqueServices $estoqueServices;
    protected array $methods;
    protected Collection $data;
    protected Collection $dataRelatorio;
    protected Generator $fake;
    protected array $datesRelatorio;

    protected function mockEstoqueRepository(array $data = [])
    {
        $this->estoqueRepository = $this->mock(
            IEstoque::class,
            function (MockInterface $mock) use ($data) {
                foreach ($data as $method => $return) {
                    $mock->shouldReceive($method)
                        ->withAnyArgs()
                        ->andReturn($return)
                        ->getMock();
                }
            }
        );
        $this->estoqueServices = new EstoqueServices($this->estoqueRepository);
    }

    protected function setUp(): void
    {
        parent::setUp();
        \DB::disconnect();
        $this->fake = Factory::create(
            \Config::get('app.faker_locale')
        );
        $this->data = collect([
            'httpHost' => env('APP_URL_FRONT'),
            'quantidade' => rand(1,9),
            'produto_id' => rand(1,9)
        ]);

        
        $data = [];
        for ($i=0; $i < 3 ; $i++) { 
            $obj = new stdClass;
            $obj->id = $this->data->get('produto_id');
            $obj->quantidade = $this->data->get('quantidade');
            $obj->acao = 'Adição';
            $obj->updated_at = now()->subDays($i)->format('Y-m-d H:i:s');
            $obj->nome = $this->fake->name();
            $obj->sku = md5(uniqid());
            $data[$i] = $obj;
        }
        $this->dataRelatorio = collect($data);
        
        $this->mockEstoqueRepository([
            'countQuantidadeProduto' => $this->data->get('quantidade'),
            'persistence' => Estoque::factory()->makeOne(),
            'relatorioMovimentos' => $this->dataRelatorio
        ]);
        $this->datesRelatorio = [
            'start_date' => now()->subDay()->format('Y-m-d H:i:s'),
            'end_date' => now()->format('Y-m-d H:i:s')
        ];
        $this->methods = ['post', 'put'];       
        
    }

    public function testSuccess()
    {
        foreach ($this->methods as $method) {
            $newData = $this->data->merge([
                'method' => $method
            ])->toArray();
            $response = $this->estoqueServices->estoque($newData);
            $this->assertInstanceOf(Estoque::class, $response);
        }
    }

    public function testExceptionInformation()
    {
        $this->expectException(Information::class);
        $data = [];
        $this->estoqueServices->estoque($data);
    }

    public function testExceptionInsufficientQuantity()
    {
        $this->expectException(InsufficientQuantity::class);
        $data = $this->data->merge([
            'method' => 'put',
            'quantidade' => $this->data->get('quantidade') + 1
        ])->toArray();
       
        $this->estoqueServices->estoque($data);
    }

    public function testRelatorio()
    {
        $response = $this->estoqueServices->relatorio($this->datesRelatorio);
        $dates = $this->dataRelatorio->map(function($value){
            return date('Y-m-d',strtotime($value->updated_at));
        })->toArray();
        $this->assertArrayIntersect($dates,array_keys($response));
        $this->assertCount(count($dates),$response);
    }

    public function testRelatorioExceptionInformacao()
    {
        $this->expectException(Information::class);
        $this->estoqueServices->relatorio([]);
    }

}
