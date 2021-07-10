<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\Sql\Nullable;
use App\Exceptions\Sql\Unique;
use App\Models\Produto;
use App\Repositories\Eloquent\ProdutoRepository;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ProdutoTest extends TestCase
{

    use DatabaseMigrations;

    private ProdutoRepository $produtoRepository;
    private Produto $fake;
    private Generator $fakeData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->produtoRepository = new ProdutoRepository;
        $this->fake = Produto::factory()->create();
        $this->fakeData = Factory::create(
            \Config::get('app.faker_locale')
        );
    }

    public function testFindProductAll()
    {
        $response = $this->produtoRepository->findAll();
        $this->assertEquals($this->fake->count(),$response->count());
    }

    public function testFindProductById()
    {
        $response = $this->produtoRepository->find($this->fake->id);
        $this->assertNotNull($response);
    }

    public function testFindProductBySku()
    {
        $response = $this->produtoRepository->findBySku($this->fake->sku);
        $this->assertNotNull($response);
    }

    public function testProductPaginate()
    {
        $response = $this->produtoRepository->paginate();
        $this->assertTrue($response instanceof LengthAwarePaginator);
        $this->fake->factory(20)->create();
        $response = $this->produtoRepository->paginate();
        $this->assertEquals($response->total(), 21);
        $response = $this->produtoRepository->paginate(2);
        $this->assertEquals(2, $response->currentPage());
        $this->assertEquals(\Config::get('app.url'),$response->path());
    }

    public function testSaveSuccess()
    {
        $sku = md5(uniqid());
        $nome = $this->fakeData->name();
        $response = $this->produtoRepository->save([
            'sku' => $sku,
            'nome' => $nome
        ]);
        $this->assertNotNull($response);
        $this->assertEquals($sku,$response->sku);
        $this->assertEquals($nome,$response->nome);
        $sku = md5(uniqid());
        $nome = $this->fakeData->name();
        $response = $this->produtoRepository->save([
            'sku' => $sku,
            'nome' => $nome
        ]);
        $this->assertEquals($sku,$response->sku);
        $this->assertEquals($nome,$response->nome);
        $this->assertEquals(3, Produto::count());
    }

    public function testUpdate(){
        $sku = md5(uniqid());
        $nome = $this->fakeData->colorName();
        $response = $this->produtoRepository->save([
            'sku' => $sku,
            'nome' => $nome
        ],$this->fake->id);
        $this->assertEquals($sku,$response->sku);
        $this->assertEquals($nome,$response->nome);
    }

    public function testFieldNullSku()
    {
        $data = [
            '',
            '   ',
            '*& ',
            null, 
            true
        ];
        $erros = 0;
        foreach($data as $dataValue){
            try {
                $this->produtoRepository->save([
                    'sku' => $dataValue
                ]);
            } catch (Nullable $thNull) {
                $erros++;
            }
        }
        $this->assertEquals(count($data),$erros);
    }

    public function testFieldUniqueSku()
    {
        $this->expectException(Unique::class);
        
        $this->produtoRepository->save([
            'sku' => $this->fake->sku,
            'nome' => $this->fakeData->colorName()
        ]);
    }

    public function testDelete()
    {
        //Soft Delete
        $response = $this->produtoRepository->destroy($this->fake->id);
        $this->assertTrue($response);
        $this->assertDatabaseCount('produtos',1);
        
        //Delete fisico
        $this->fake->restore();
        $response = $this->produtoRepository->destroy($this->fake->id,true);
        $this->assertTrue($response);
        $this->assertDatabaseCount('produtos',0);

    }

    
}
