<?php

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

    protected function requesClass(): string
    {
        return ProdutoRequest::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        \DB::disconnect();
        $this->data = collect([]);
    }

    public function testSucess()
    {
        $data = [
            'asdcqweqwe',
            12341241231231,
            'asds123123',
            'asdA213ASD'
        ];
        foreach($data as $dataValue){
            $dataInsert = $this->data->merge([
                'sku' => $dataValue
            ])->toArray();
            $this->assertSuccessValidator(data: $dataInsert);   
        }
    }

    public function testInvalidationFieldSku()
    {
        
        $data = $this->data->except('sku')->toArray();
        $this->assertInvalidationFieldRule($data, 'required');
        $data = [
            null,
            '',
            " ",
            "   "
        ];
        foreach($data as $dataValue){
            $dataInsert = $this->data->merge([
                'sku' => $dataValue
            ])->toArray();
            $this->assertInvalidationFieldRule($dataInsert, 'required');
        }

        $data = [
            'A8&%#%*79',
            true,
            1.0123,
            'as@asd12.com'
        ];
        foreach($data as $dataValue){
            $dataInsert = $this->data->merge([
                'sku' => $dataValue
            ])->toArray();
            $this->assertInvalidationFieldRule($dataInsert, 'alpha_num');
        }
    }
}
