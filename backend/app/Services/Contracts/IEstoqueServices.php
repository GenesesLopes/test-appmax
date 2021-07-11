<?php
declare (strict_types = 1);

namespace App\Services\Contracts;

use App\Http\Requests\EstoqueRequest;
use App\Models\Estoque;

interface IEstoqueServices
{
    public function movimentacao(EstoqueRequest $estoqueRequest): Estoque;
}