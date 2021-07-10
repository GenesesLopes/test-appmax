<?php
declare (strict_types = 1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface IEstoque 
{
    public function add(array $data): Model;
    public function findProduto(array $data): Model;
}
