<?php

declare (strict_types = 1);

namespace App\Repositories\Eloquent;

use App\Models\Estoque;
use App\Models\Movimentacao;
use App\Repositories\Contracts\IEstoque;
use Illuminate\Database\Eloquent\Model;

class EstoqueRepository implements IEstoque
{
    public function findProduto(array $data): Model
    {
        return Estoque::firstOrNew(
            ['produto_id' => $data['produto_id']],
            [
                'produto_id' => $data['produto_id'],
                'quantidade' => $data['quantidade']
            ]
        );
    }

    public function add(array $data): Model
    {
        try {
            \DB::beginTransaction();
            $estoque = $this->findProduto($data);
            \DB::commit();
            return $estoque;
        } catch (\Exception $th) {
            \DB::rollBack();
        }
    }

}