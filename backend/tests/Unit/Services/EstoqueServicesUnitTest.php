<?php

namespace Tests\Unit\Services;

use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use App\Services\EstoqueServices;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class EstoqueServicesUnitTest extends TestCase
{
    protected IEstoque $estoqueRepository;
    protected EstoqueServices $estoqueServices;
    protected array $methods;
    protected Collection $data;
    protected Generator $fake;

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
        $this->data = collect([
            'httpHost' => env('APP_URL_FRONT'),
            'quantidade' => rand(1,9),
            'produto_id' => rand(1,9)
        ]);
        $this->mockEstoqueRepository([
            'countQuantidade' => $this->data->get('quantidade'),
            'persistence' => Estoque::factory()->makeOne()
        ]);
        $this->estoqueServices = new EstoqueServices($this->estoqueRepository);
        $this->methods = ['post', 'put'];
        $this->fake = Factory::create(
            \Config::get('app.faker_locale')
        );
        
        
    }

    public function testSuccess()
    {
        foreach ($this->methods as $method) {
            $newData = $this->data->merge([
                'method' => $method
            ])->toArray();
            $response = $this->estoqueServices->movimentacao($newData);
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
