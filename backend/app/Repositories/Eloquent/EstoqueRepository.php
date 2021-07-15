<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class EstoqueRepository implements IEstoque
{

    private string $sqlEstoque = "SELECT 
    table_estoque.*
    FROM (
        SELECT produtos.id, 
        produtos.nome,
        produtos.sku,
        estoques.acao, 
        sum(estoques.quantidade) as total_somado
        FROM estoques
        JOIN produtos ON estoques.produto_id = produtos.id
        WHERE estoques.acao = 'Adição'
        AND produtos.deleted_at IS NULL
        GROUP BY produtos.id
        UNION
        SELECT produtos.id,
        produtos.nome,
        produtos.sku,
        estoques.acao,
        sum(estoques.quantidade) as total_somado
        FROM estoques
        JOIN produtos ON estoques.produto_id = produtos.id
        WHERE estoques.acao = 'Remoção' 
        AND produtos.deleted_at IS NULL
        GROUP BY produtos.id
    ) AS table_estoque
    ORDER BY id";

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

    public function paginate(int $page = 0, int $perPage = 15): Collection
    {
        $queryData = \DB::select(
            $this->sqlEstoque . " LIMIT ?,?",
            [
                $page,
                $perPage
            ]

        );
        $total = 0;
        if (count($queryData)) {
            $total = (int) Estoque::whereNotIn('produto_id', function (Builder $query) {
                $query->select('id')
                    ->from('produtos')
                    ->whereNotNull('deleted_at')
                    ->get();
            })->groupBy('produto_id')
                ->select('produto_id')
                ->get()->count();
        }


        return new Collection([
            'itens' => $queryData,
            'total' => $total
        ]);
    }

    public function relatorioMovimentos(array $data): Collection
    {
        return \DB::table('estoques')
            ->join('produtos', 'estoques.produto_id', '=', 'produtos.id')
            ->whereBetween('estoques.updated_at', [
                $data['start_date'],
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
        $idProduto2 = $idProduto;
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
                $idProduto,
                $idProduto2
            ]
        );
        return (int)$total[0]->quantidade_total;
    }

    public function QuantidadeEstoqueBaixa(): Collection
    {
        $query = \DB::select($this->sqlEstoque);
        return new Collection($query);
    }
}
