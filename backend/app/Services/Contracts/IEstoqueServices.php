<?php
declare (strict_types = 1);

namespace App\Services\Contracts;

use App\Models\Estoque;

interface IEstoqueServices
{
    public function movimentacao(array $data): Estoque;
}