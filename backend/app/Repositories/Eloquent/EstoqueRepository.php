<?php

declare (strict_types = 1);

namespace App\Repositories\Eloquent;

use App\Models\Estoque;
use App\Models\Movimentacao;
use App\Repositories\Contracts\IEstoque;
use App\Repositories\Contracts\IMovimentacao;
use Illuminate\Database\Eloquent\Model;

class EstoqueRepository implements IEstoque
{

    public function __construct(
        public IMovimentacao $iMovimentacao
    )
    {
    }

    public function findProduto(array $data): Estoque
    {
        return Estoque::firstOrNew(
            ['produto_id' => $data['produto_id']],
            [
                'produto_id' => $data['produto_id'],
                'quantidade' => $data['quantidade']
            ]
        );
    }

    public function add(array $data): Estoque
    {
        try {
            \DB::beginTransaction();
            $estoque = $this->findProduto($data);
            $estoque->quantidade++;
            $estoque->save();
            $data['produto_id'] = $estoque->id;
            $this->iMovimentacao->add($data);
            \DB::commit();
            return $estoque;
        } catch (\Exception $th) {
            \DB::rollBack();
        }
    }

}