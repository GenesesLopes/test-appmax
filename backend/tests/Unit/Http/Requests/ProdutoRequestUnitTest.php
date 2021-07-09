<?php
declare (strict_types = 1);

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\ProdutoRequest;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\Unit\Http\Requests\Traits\ValidatorTrait;

class ProdutoRequestUnitTest extends TestCase
{
    use ValidatorTrait;

    private ProdutoRequest $request;
    private Collection $data;
    private array $methods;

    protected function requesClass(): string
    {
        return ProdutoRequest::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        \DB::disconnect();
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
}
