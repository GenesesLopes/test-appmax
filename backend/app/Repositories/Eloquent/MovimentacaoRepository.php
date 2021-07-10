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
        return Movimentacao::create($data);
    }

}