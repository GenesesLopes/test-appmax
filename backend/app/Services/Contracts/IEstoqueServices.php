<?php
declare (strict_types = 1);

namespace App\Services\Contracts;

use App\Models\Estoque;
use Illuminate\Pagination\LengthAwarePaginator;

interface IEstoqueServices
{
    public function estoque(array $data): Estoque;

    public function relatorio(array $data): array;

    public function listagem(int $page, int $perPage = 15): LengthAwarePaginator;

    public function quantidadeBaixa();
}