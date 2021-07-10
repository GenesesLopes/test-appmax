<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\ProdutoRequest;
use App\Models\Produto;
use App\Repositories\Contracts\IProduto;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Unit\Http\Requests\Traits\ValidatorTrait;

class ProdutoRequestUnitTest extends TestCase
{
    use ValidatorTrait;

    private ProdutoRequest $request;
    private Collection $data;
    private Collection $dataMock;
    private array $methods;
    protected IProduto $produtoRepository;
    protected Generator $fake;

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

    protected function mockRepository(array $data = [])
    {
        $this->produtoRepository = $this->mock(
            IProduto::class,
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
        $this->fake = Factory::create(
            \Config::get('app.faker_locale')
        );
        $this->dataMock = collect([
            'findBySku' => null,
            'find' => Produto::factory()->makeOne()
        ]);
        $this->mockRepository($this->dataMock->toArray());
        $this->data = collect([]);
        $this->methods = ['post', 'put'];
    }

    public function testSucess()
    {
        foreach ($this->methods as $method) {
            $data = [
                [
                    'sku' => 'asdcqweqwe',
                    'nome' => $this->fake->colorName()
                ],
                [
                    'sku' => 12341241231231,
                    'nome' => $this->fake->name()
                ],
                [
                    'sku' => 'asds123123',
                    'nome' => $this->fake->text()
                ],
                [
                    'sku' => 'asdA213ASD',
                    'nome' => $this->fake->city()
                ]
            ];
            foreach ($data as $dataValue) {
                $this->assertSuccessValidator(data: $dataValue, method: $method);
            }
        }
    }

    public function testInvalidationFieldSku()
    {
       $this->data = $this->data->merge(['nome' => $this->fake->colorName()]);
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

    public function testInvalidationFieldNome()
    {
       $this->data = $this->data->merge(['sku' => $this->fake->randomNumber(4)]);
        foreach ($this->methods as $method) {
            $data = $this->data->except('nome')->toArray();
            $this->assertInvalidationFieldRule($data, 'required', method: $method);
            $data = [
                null,
                '',
                " ",
                "   "
            ];
            foreach ($data as $dataValue) {
                $dataInsert = $this->data->merge([
                    'nome' => $dataValue
                ])->toArray();
                $this->assertInvalidationFieldRule($dataInsert, 'required', method: $method);
            }

            $data = [
                true,
                1.0123,
                1234412
            ];
            foreach ($data as $dataValue) {
                $dataInsert = $this->data->merge([
                    'nome' => $dataValue
                ])->toArray();
                $this->assertInvalidationFieldRule($dataInsert, 'string', method: $method);
            }
        }
    }

    public function testSkuExists()
    {
        $produto = Produto::factory()->makeOne();
        $newReturn = [
            'find' => $this->dataMock->get('find'),
            'findBySku' => $produto
        ];
        $this->mockRepository(
            $newReturn
        );
        $error = [
            'sku' => 'Sku já presente na aplicação'
        ];

        $this->assertCustomInvalidation(
            $produto->toArray(),
            $error
        );
    }

    public function testIdProductNotExists()
    {
        $produto = Produto::factory()->makeOne();
        $newReturn = [
            'find' => null,
            'findBySku' => null
        ];

        $this->mockRepository(
            $newReturn
        );
        $error = [
            'id' => 'Produto não encontrado'
        ];

        $this->assertCustomInvalidation(
            $produto->toArray(),
            $error,
            'delete',
            ['id' => 1]
        );
    }
}
