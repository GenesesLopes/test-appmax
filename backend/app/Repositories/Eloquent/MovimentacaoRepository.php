<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Movimentacao;
use App\Repositories\Contracts\IMovimentacao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MovimentacaoRepository implements IMovimentacao
{

    public function add(array $data): Model
    {
        return Movimentacao::create([
            'produto_id' => $data['produto_id'],
            'quantidade' => $data['quantidade'],
            'acao' => $data['acao'],
            'origem' => $data['origem']
        ]);
    }

    public function relatorioMovimentos(array $data): Collection
    {
        
        $data['start_date'] = $data['start_date'] . ' 00:00:00';
        $data['end_date'] = $data['end_date'] . ' 23:59:59';
        // dump($data);
        $query = \DB::table('movimentacoes')
            ->join('produtos', 'movimentacoes.produto_id', '=', 'produtos.id')
            // ->whereNull(['produtos.deleted_at'])
            ->whereBetween('movimentacoes.updated_at',[
                $data['start_date'] ,
                $data['end_date']
            ])
            ->whereNull(['produtos.deleted_at'])
            ->orderBy('movimentacoes.updated_at')
            ->select([
                'produtos.id',
                'movimentacoes.quantidade',
                'acao',
                'origem',
                'movimentacoes.updated_at',
                'nome',
                'sku'
            ])
            ->get();
        return $query;
    }
}
