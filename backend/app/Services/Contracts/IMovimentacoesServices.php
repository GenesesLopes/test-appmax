<?php
declare (strict_types = 1);

namespace App\Services\Contracts;

interface IMovimentacoesServices
{
    public function relatorio(array $data);
}