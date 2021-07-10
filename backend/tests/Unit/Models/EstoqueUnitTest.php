<?php

namespace Tests\Unit\Models;

use App\Models\Estoque;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\Traits\TestArrayIntersect;

class EstoqueUnitTest extends TestCase
{
    use TestArrayIntersect;

    private Estoque $estoque;

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
            'produtos_id',
            'quantidade' 
        ];
        $fillable = $this->estoque->getFillable();
        $this->assertEquals($fillables,$fillable);
    }

    public function testGetDates()
    {
        $dates = [
           'deleted_at',
           'created_at',
           'updated_at'
        ];

        $getDates = $this->estoque->getDates();

        $this->assertEquals($dates, $getDates);
    }

    public function testUseTraits()
    {
        $traits = [
            HasFactory::class,
            SoftDeletes::class
        ];

        $estoqueTraits = array_keys(class_uses(Estoque::class));
        $this->assertEquals($traits, $estoqueTraits);
    }

    public function testCasts()
    {
        $casts = [
            'quantidade'=> 'integer',
            'produtos_id' => 'integer'
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
