<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\EstoqueRequest;
use App\Models\Produto;
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


    protected function instanceRequest(
        array $data = [],
        string $method = 'post',
        array $query = []
    ): void {

        $this->request = new EstoqueRequest(
            $this->produtoRepository,
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

    private function getQuantidade(): int
    {
        do {
            $quantidade = $this->fake->numberBetween(-2, 4);
        } while ($quantidade == 0);
        return $quantidade;
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
        $this->data = collect([
            'produto_id' => $this->produto->id,
            'quantidade' =>  $this->getQuantidade()
        ]);
        $this->mockProdutoRepository([
            'find' => $this->produto
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

    public function testProductIdZero()
    {
        $data = $this->data->merge([
            'produto_id' => 0
        ]);
        $error = [
            'quantidade' => 'A quantidade deve ser diferente de 0'
        ];
        $this->assertCustomInvalidation($data->toArray(),$error);
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
}
