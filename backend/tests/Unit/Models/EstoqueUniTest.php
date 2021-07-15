<?php

namespace Tests\Unit\Models;

use App\Models\Estoque;
use Tests\TestCase;
use Tests\Traits\TestArrayIntersect;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstoqueUniTest extends TestCase
{
    use TestArrayIntersect;

    private Estoque $Estoque;

    protected function setUp(): void
    {
        parent::setUp();
        $this->estoque = new Estoque();
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
        $fillable = $this->estoque->getFillable();
        $this->assertEquals($fillables,$fillable);
    }

    public function testUseTraits()
    {
        $traits = [
            HasFactory::class
        ];

        $EstoqueTraits = array_keys(class_uses(Estoque::class));
        $this->assertEquals($traits, $EstoqueTraits);
    }

    public function testCasts()
    {
        $casts = [
            'quantidade'=> 'integer',
            'produto_id' => 'integer'
        ];
        $getCasts = $this->estoque->getCasts();

        $this->assertArrayIntersect(
            $casts,
            $getCasts,
            true
        );

    }

    public function testIncrementing()
    {

        $this->assertTrue($this->estoque->incrementing);
    }

    public function testKeyTypes()
    {
        $keyType = 'int';
        $this->assertEquals($keyType, $this->estoque->getKeyType());
    }

    public function testTableName()
    {
        $tableName = 'estoques';
        $this->assertEquals($tableName,$this->estoque->getTable());
    }
}
