<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\EstoqueRequest;
use App\Models\Produto;
use App\Repositories\Contracts\IEstoque;
use App\Repositories\Contracts\IProduto;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Unit\Http\Requests\Traits\ValidatorTrait;

class EstoqueRequestUnitTest extends TestCase
{
    use ValidatorTrait;

    private EstoqueRequest $request;
    private Collection $data;
    private Produto $produto;
    protected Generator $fake;    
    protected IProduto $produtoRepository;
    protected IEstoque $estoqueRepository;
    private int $quantidade;


    protected function instanceRequest(
        array $data = [],
        string $method = 'post',
        array $query = []
    ): void {

        $this->request = new EstoqueRequest(
            $this->produtoRepository,
            $this->estoqueRepository,
            $query,
            $data
        );
        $this->request->setMethod($method);
        $this->request->setContainer(app())
            ->setRedirector(app(Redirector::class))
            ->validateResolved();
    }

    protected function mockProdutoRepository(array $data = [])
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
        $this->fake = Factory::create(
            \Config::get('app.faker_locale')
        );
        $this->produto = Produto::factory()->make([
            'id' => $this->fake->randomDigitNotZero(),
        ]);
        $this->quantidade = $this->fake->numberBetween(1, 4);
        $this->data = collect([
            'produto_id' => $this->produto->id,
            'quantidade' =>  $this->quantidade
        ]);
        $this->mockProdutoRepository([
            'find' => $this->produto
        ]);
        $this->mockEstoqueRepository([
            'countQuantidadeProduto' => $this->quantidade
        ]);
    }

    public function testSuccess()
    {   
        $this->assertSuccessValidator(data: $this->data->toArray());
    }

    public function testFailFields()
    {
        $fields = [
            'produto_id',
            'quantidade'
        ];
        foreach ($fields as $field) {
            $data = [
                'required' => [
                    '',
                    null,
                    '   '
                ],
                'integer' => [
                    'asdas',
                    false,
                    'true',
                    123.412
                ]
            ];
            foreach ($data as $rule => $values) {
                foreach ($values as $value) {
                    $newData = $this->data->merge([
                        $field => $value
                    ])->toArray();
                    $this->assertInvalidationFieldRule($newData, $rule);
                }
            }
        }
    }

    public function testQuantityLessThanOrEqualToZero()
    {
        $data = [
            0,
            -1,
            -4
        ];
        foreach($data as $dataValue){
            $newData = $this->data->merge([
                'quantidade' => $dataValue
            ]);
            $this->assertInvalidationFieldRule($newData->toArray(),'gt.numeric',['value' => 0]);
        }
    }

    public function testIdProductNotExists()
    {
        $newReturn = [
            'find' => null,
        ];

        $this->mockProdutoRepository(
            $newReturn
        );
        $error = [
            'produto_id' => 'Produto não encontrado'
        ];

        $this->assertCustomInvalidation(
            $this->data->toArray(),
            $error,
        );
    }

    public function testQuantityGreaterThanCurrent()
    {
        $newReturn = [
            'countQuantidadeProduto' => $this->quantidade
        ];

        $this->mockEstoqueRepository(
            $newReturn
        );
        $error = [
            'quantidade' => 'Quantidade a ser removida deve ser igual ou superior à quantidade em estoque'
        ];
        $newData = $this->data->merge([
            'quantidade' => $this->quantidade + 1
        ])->toArray();
        $this->assertCustomInvalidation(
            $newData,
            $error,
            'put'
        );
    }
}
