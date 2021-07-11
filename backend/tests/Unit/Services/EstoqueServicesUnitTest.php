<?php

namespace Tests\Unit\Services;

use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use App\Services\EstoqueServices;
use Mockery\MockInterface;
use Tests\TestCase;

class EstoqueServicesUnitTest extends TestCase
{
    protected IEstoque $estoqueRepository;
    protected EstoqueServices $estoqueServices;
    protected array $methods;

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
    }

    protected function setUp(): void
    {
        parent::setUp();
        \DB::disconnect();
        $this->mockEstoqueRepository([
            'add' => Estoque::factory()->make(),
            'remove' => Estoque::factory()->make()
        ]);
        $this->estoqueServices = new EstoqueServices($this->estoqueRepository);
        $this->methods = ['post', 'put'];
    }

    public function testSuccess()
    {
        foreach ($this->methods as $method) {
            $data = [
                'method' => $method,
                'httpHost' => env('APP_URL_FRONT')
            ];
            $response = $this->estoqueServices->movimentacao($data);
            $this->assertInstanceOf(Estoque::class, $response);
        }
    }

    public function testException()
    {
        $this->expectException(\Exception::class);
        $data = [];
        $this->estoqueServices->movimentacao($data);
    }
}
