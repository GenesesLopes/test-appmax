<?php

namespace Tests\Unit\Services;

use App\Exceptions\Utils\Information;
use App\Exceptions\Utils\InsufficientQuantity;
use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use App\Services\EstoqueServices;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class EstoqueServicesUnitTest extends TestCase
{
    protected IEstoque $estoqueRepository;
    protected EstoqueServices $estoqueServices;
    protected array $methods;
    protected Collection $data;

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
        $this->data = collect([
            'httpHost' => env('APP_URL_FRONT'),
            'quantidade' => rand(1,9),
            'produto_id' => rand(1,9)
        ]);
        $this->mockEstoqueRepository([
            'countQuantidade' => $this->data->get('quantidade'),
            'persistence' => Estoque::factory()->makeOne()
        ]);
        
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

}
