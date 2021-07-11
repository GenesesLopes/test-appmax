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
        $self = $this;
        return \DB::transaction(function() use ($self, $data){
            $estoque = $self->findProduto($data);
            $estoque->quantidade += $data['quantidade'];
            $estoque->save();
            $data['produto_id'] = $estoque->id;
            $self->iMovimentacao->add($data);
            return $estoque;
        });
    }

    public function remove(array $data): Estoque
    {
        $self = $this;
        return \DB::transaction(function() use ($self, $data){
            $estoque = $self->findProduto($data);
            $estoque->quantidade -= $data['quantidade'];
            $estoque->save();
            $data['produto_id'] = $estoque->id;
            $self->iMovimentacao->add($data);
            return $estoque;
        });
    }

    public function countQuantidade(int $idProduto): int
    {
        return Estoque::where('produto_id',$idProduto)->sum('quantidade');
    }

}