<?php
declare (strict_types = 1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IMovimentacao
{
    public function add(array $data): Model;

    public function relatorioMovimentos(array $data): Collection;
}