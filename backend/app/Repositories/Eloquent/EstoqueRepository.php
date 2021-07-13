<?php

declare (strict_types = 1);

namespace App\Repositories\Eloquent;

use App\Models\Estoque;
use App\Models\Movimentacao;
use App\Repositories\Contracts\IEstoque;
use App\Repositories\Contracts\IMovimentacao;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;

class EstoqueRepository implements IEstoque
{

    public function __construct(
        public IMovimentacao $iMovimentacao
    )
    {
    }

    public function findProduto(array $data): ?Estoque
    {
        $estoque = Estoque::where('produto_id',$data['produto_id'])->first();
        if($estoque == null)
            return null;
        return $estoque;
    }

    public function add(array $data): Estoque
    {
        $self = $this;
        return \DB::transaction(function() use ($self, $data){
            $estoque = $self->findProduto($data);
            if($estoque === null){
                $estoque = Estoque::create([
                    'produto_id' => $data['produto_id'],
                    'quantidade' => $data['quantidade']
                ]);
            }else{
                $estoque->quantidade += $data['quantidade'];
                $estoque->save();
            }            
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
        return (int)Estoque::where('produto_id',$idProduto)->sum('quantidade');
    }

    public function paginate(int $page = 1, int $perPage = 15): LengthAwarePaginator
    {
        return Estoque::paginate($perPage, page: $page);
    }

    public function QuantidadeEstoque(): SupportCollection
    {
        return \DB::table('estoques')
            ->join('produtos','estoques.produto_id','=','produtos.id')
            ->whereNull(['estoques.deleted_at','produtos.deleted_at'])
            ->select([
                'produtos.id',
                'produtos.nome',
                'produtos.sku',
                \DB::raw('SUM(estoques.quantidade) as total_estoque')
            ])
            ->groupBy('produtos.id')
            ->havingRaw('SUM(estoques.quantidade) < ?',[100])
            ->get();
    }

}