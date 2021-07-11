<?php
declare (strict_types = 1);

namespace App\Repositories\Eloquent;

use App\Models\Movimentacao;
use App\Repositories\Contracts\IMovimentacao;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoRepository implements IMovimentacao
{

    public function add(array $data): Model
    {
        // dd($data);
        return Movimentacao::create([
            'produto_id' => $data['produto_id'],
            'quantidade' => $data['quantidade'],
            'acao' => $data['acao'],
            'origem' => $data['origem']
        ]);
    }

}