<?php
declare (strict_types = 1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface IMovimentacao
{
    public function add(array $data): Model;
}