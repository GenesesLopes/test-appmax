<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EstoqueRepository implements IEstoque
{

    public function findProduto(int $id_produto): ?Estoque
    {
        return Estoque::where('produto_id', $id_produto)->first();
    }

    public function persistence(Estoque $estoque): Estoque
    {
        $estoque->save();
        $estoque->refresh();
        return $estoque;
    }

    public function paginate(int $page = 1, int $perPage = 15): LengthAwarePaginator
    {
        return Estoque::paginate($perPage, page: $page);
    }

    public function relatorioMovimentos(array $data): Collection
    {
        return \DB::table('estoques')
            ->join('produtos', 'estoques.produto_id', '=', 'produtos.id')
            ->whereBetween('estoques.updated_at',[
                $data['start_date'] ,
                $data['end_date']
            ])
            ->whereNull(['produtos.deleted_at'])
            ->orderBy('estoques.updated_at')
            ->select([
                'produtos.id',
                'estoques.quantidade',
                'acao',
                'origem',
                'estoques.updated_at',
                'nome',
                'sku'
            ])->get(); 
    }

    public function countQuantidadeProduto(int $idProduto): int
    {
        $total = \DB::select(
            "SELECT IF(soma_adicao IS NULL, 0, soma_adicao) - IF(soma_remocao IS NULL, 0, soma_remocao) AS quantidade_total
             FROM (
                SELECT sum(estoques.quantidade) as soma_adicao
                FROM estoques
                JOIN produtos ON estoques.produto_id = produtos.id
                WHERE estoques.acao = 'Adição'
                AND produtos.deleted_at IS NULL
                AND estoques.produto_id = ?
            ) AS query_adicao,
            (
                SELECT sum(estoques.quantidade) as soma_remocao
                FROM estoques
                JOIN produtos ON estoques.produto_id = produtos.id
                WHERE estoques.acao = 'Remoção' 
                AND produtos.deleted_at IS NULL
                AND estoques.produto_id = ?
            ) AS query_remocao",
            [
                1, $idProduto
            ]
        );
        return (int)$total[0]->quantidade_total;
    }

    public function QuantidadeEstoqueBaixa(): Collection
    {
        $query = \DB::select(
            "SELECT produtos.id, produtos.nome, produtos.sku, estoques.acao, sum(estoques.quantidade) as total_estoque
            FROM estoques
            JOIN produtos ON estoques.produto_id = produtos.id
            WHERE estoques.acao = 'Adição'
            AND produtos.deleted_at IS NULL
            GROUP BY produtos.id
            HAVING sum(estoques.quantidade) < 100
            UNION
            SELECT produtos.id, produtos.nome, produtos.sku, estoques.acao, sum(estoques.quantidade) as total_estoque
            FROM estoques
            JOIN produtos ON estoques.produto_id = produtos.id
            WHERE estoques.acao = 'Remoção' 
            AND produtos.deleted_at IS NULL
            GROUP BY produtos.id
            HAVING sum(estoques.quantidade) < 100"
        );
        return new Collection($query);
    }
}