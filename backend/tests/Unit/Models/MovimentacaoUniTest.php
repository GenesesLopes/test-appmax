<?php

namespace Tests\Unit\Models;

use App\Models\Movimentacao;
use Tests\TestCase;
use Tests\Traits\TestArrayIntersect;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovimentacaoUniTest extends TestCase
{
    use TestArrayIntersect;

    private Movimentacao $movimentacao;

    protected function setUp(): void
    {
        parent::setUp();
        $this->movimentacao = new Movimentacao();
        \DB::disconnect();        
    }

    public function testFillable()
    {
        $fillables = [
            'id',
            'produto_id',
            'quantidade',
            'acao',
            'origem'
        ];
        $fillable = $this->movimentacao->getFillable();
        $this->assertEquals($fillables,$fillable);
    }

    public function testUseTraits()
    {
        $traits = [
            HasFactory::class
        ];

        $movimentacaoTraits = array_keys(class_uses(Movimentacao::class));
        $this->assertEquals($traits, $movimentacaoTraits);
    }

    public function testCasts()
    {
        $casts = [
            'quantidade'=> 'integer',
            'produto_id' => 'integer'
        ];
        $getCasts = $this->movimentacao->getCasts();

        $this->assertArrayIntersect(
            $casts,
            $getCasts,
            true
        );

    }

    public function testIncrementing()
    {

        $this->assertTrue($this->movimentacao->incrementing);
    }

    public function testKeyTypes()
    {
        $keyType = 'int';
        $this->assertEquals($keyType, $this->movimentacao->getKeyType());
    }

    public function testTableName()
    {
        $tableName = 'movimentacoes';
        $this->assertEquals($tableName,$this->movimentacao->getTable());
    }
}
