<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\ProdutoRequest;
use App\Models\Produto;
use App\Repositories\Contracts\IProduto;
use Arr;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Unit\Http\Requests\Traits\ValidatorTrait;

class ProdutoRequestUnitTest extends TestCase
{
    use ValidatorTrait;

    private ProdutoRequest $request;
    private Collection $data;
    private array $methods;
    protected IProduto $produtoRepository;

    protected function instanceRequest(
        array $data = [],
        string $method = 'post',
        array $query = []
    ): void {

        $newData = count($data) || (count(array_keys($this->data->toArray())) === 1 && !count($data))
            ? $data
            : $this->data->toArray();
        $this->request = new ProdutoRequest(
            $this->produtoRepository,
            $query,
            $newData
        );
        $this->request->setMethod($method);
        $this->request->setContainer(app())
            ->setRedirector(app(Redirector::class))
            ->validateResolved();
    }

    protected function mockRepository(array|Object|null $data = null)
    {
        $this->produtoRepository = $this->mock(
            IProduto::class,
            function (MockInterface $mock) use ($data) {
                $mock->shouldReceive('findBySku')
                    ->andReturn($data)
                    ->getMock();
            }
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        \DB::disconnect();
        $this->mockRepository();
        $this->data = collect([]);
        $this->methods = ['post', 'put'];
    }

    public function testSucess()
    {
        foreach ($this->methods as $method) {
            $data = [
                'asdcqweqwe',
                12341241231231,
                'asds123123',
                'asdA213ASD'
            ];
            foreach ($data as $dataValue) {
                $dataInsert = $this->data->merge([
                    'sku' => $dataValue
                ])->toArray();
                $this->assertSuccessValidator(data: $dataInsert, method: $method);
            }
        }
    }

    public function testInvalidationFieldSku()
    {
        foreach ($this->methods as $method) {
            $data = $this->data->except('sku')->toArray();
            $this->assertInvalidationFieldRule($data, 'required', method: $method);
            $data = [
                null,
                '',
                " ",
                "   "
            ];
            foreach ($data as $dataValue) {
                $dataInsert = $this->data->merge([
                    'sku' => $dataValue
                ])->toArray();
                $this->assertInvalidationFieldRule($dataInsert, 'required', method: $method);
            }

            $data = [
                'A8&%#%*79',
                true,
                1.0123,
                'as@asd12.com'
            ];
            foreach ($data as $dataValue) {
                $dataInsert = $this->data->merge([
                    'sku' => $dataValue
                ])->toArray();
                $this->assertInvalidationFieldRule($dataInsert, 'alpha_num', method: $method);
            }
        }
    }

    public function testSkuExists()
    {
        $produto = Produto::factory()->makeOne();
        $this->mockRepository(
            $produto
        );
        $error = [
            'sku' => 'Sku já presente na aplicação'
        ];

        $this->assertCustomInvalidation(
            $produto->toArray(),
            $error
        );
        
    }
}
