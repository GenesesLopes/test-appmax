<?php

namespace Database\Seeders;

use App\Models\Estoque;
use App\Models\Produto;
use App\Services\Contracts\IEstoqueServices;
use Illuminate\Database\Seeder;

class EstoqueSeeder extends Seeder
{

    public function __construct(
        private IEstoqueServices $iEstoqueServices
    )
    {   
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Produto */
        $produto = Produto::all()->random();
        for ($i=0; $i < 3 ; $i++) { 
            $quantidade = rand(1,3);
            $method = rand(0, 2) % 2 === 0 ? 'post' : 'put';
            $httpHost = rand(0, 2) % 2 === 0 ? env('APP_URL_FRONT') : 'siynfony';
            $data = [
                'quantidade' => $quantidade,
                'method' => $method,
                'httpHost' => $httpHost,
                'produto_id' => $produto->id
            ];
            $this->iEstoqueServices->movimentacao($data);
        }
    }
}
