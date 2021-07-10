<?php

namespace Tests\Unit\Models;

use App\Models\Produto;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoUnitTest extends TestCase
{
    private Produto $produto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->produto = new Produto;
        \DB::disconnect();        
    }

    public function testFillable()
    {
        $fillables = [
            'id',
            'nome',
            'sku',
            'deleted_at'
        ];
        $fillable = $this->produto->getFillable();
        $this->assertEquals($fillables,$fillable);
    }

    public function testGetDates()
    {
        $dates = [
           'deleted_at',
           'created_at',
           'updated_at'
        ];

        $getDates = $this->produto->getDates();

        $this->assertEquals($dates, $getDates);
    }

    public function testUseTraits()
    {
        $traits = [
            HasFactory::class,
            SoftDeletes::class
        ];

        $produtoTraits = array_keys(class_uses(Produto::class));
        $this->assertEquals($traits, $produtoTraits);
    }

    public function testIncrementing()
    {

        $this->assertTrue($this->produto->incrementing);
    }

    public function testKeyTypes()
    {
        $keyType = 'int';
        $this->assertEquals($keyType, $this->produto->getKeyType());
    }

    public function testTableName()
    {
        $tableName = 'produtos';
        $this->assertEquals($tableName,$this->produto->getTable());
    }
}
