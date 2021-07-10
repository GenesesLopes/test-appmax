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
            ['produtos_id' => $data['produtos_id']],
            $data
        );
    }

    public function save(Model $model, array $data): Model
    {
        return \DB::transaction(function()use($data, $model){
            $model = $model->save();
            // Movimentacao::where('produtos_id', $data['produto_id'])
            // ->update($data);
        });
    }

}