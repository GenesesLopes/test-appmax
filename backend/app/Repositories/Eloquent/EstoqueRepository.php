<?php

declare (strict_types = 1);

namespace App\Repositories\Eloquent;

use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EstoqueRepository implements IEstoque
{

    public function create(array $data): Estoque
    {
        return Estoque::create($data);
    }

    public function findProduto(int $id_produto): ?Estoque
    {
        return Estoque::where('produto_id',$id_produto)->first();
    }

    public function persistence(Estoque $estoque): Estoque
    {
        $estoque->save();
        $estoque->refresh();
        return $estoque;
    }
    public function countQuantidade(int $idProduto): int
    {
        return (int)Estoque::where('produto_id',$idProduto)->sum('quantidade');
    }

    public function paginate(int $page = 1, int $perPage = 15): LengthAwarePaginator
    {
        return Estoque::paginate($perPage, page: $page);
    }

    public function QuantidadeEstoque(): Collection
    {
        return \DB::table('estoques')
            ->join('produtos','estoques.produto_id','=','produtos.id')
            ->whereNull('produtos.deleted_at')
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