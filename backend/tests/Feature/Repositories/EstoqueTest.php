<?php

namespace Tests\Feature\Repositories;

use App\Models\Estoque;
use App\Models\Produto;
use App\Repositories\Eloquent\EstoqueRepository;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestArrayIntersect;

class EstoqueTest extends TestCase
{
    use DatabaseMigrations, TestArrayIntersect;

    private EstoqueRepository $estoqueRepository;
    private Generator $fakeData;
    private Estoque|Collection $estoque;

    private function createEstoque(int $qtd = 1): void
    {
        $this->estoque = Estoque::factory()
            ->count($qtd)
            ->for(
                Produto::factory()->create()
            )->create();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->estoqueRepository = new EstoqueRepository;
        $this->fakeData = Factory::create(
            \Config::get('app.faker_locale')
        );
        $this->createEstoque();
    }

    public function testFindProdutoEstoque()
    {
        $firstEstoque = $this->estoque->first();
        $response = $this->estoqueRepository->findProduto($firstEstoque->getAttributes());
        $this->assertArrayIntersect(
            $firstEstoque->getAttributes(),
            $response->getAttributes(),
            true
        );
    }

    public function testFindOrCreateProdutoEstoque()
    {
        $data = [
            'produto_id' => Produto::factory()->create()->id,
            'quantidade' => $this->fakeData->randomNumber(2)
        ];
        
        $response = $this->estoqueRepository->findProduto($data);
        $this->assertArrayIntersect(
            $data,
            $response->getAttributes()
        );
    }
}
