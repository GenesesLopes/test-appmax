<?php
declare (strict_types = 1);

namespace App\Repositories\Contracts;

use App\Models\Estoque;

interface IEstoque 
{
    public function add(array $data): Estoque;
    public function findProduto(array $data): Estoque;
}
