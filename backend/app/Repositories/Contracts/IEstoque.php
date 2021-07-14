<?php
declare (strict_types = 1);

namespace App\Repositories\Contracts;

use App\Models\Estoque;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IEstoque 
{
    public function persistence(Estoque $estoque): Estoque;
    public function create(array $data): Estoque;
    public function findProduto(int $id_produto): ?Estoque;
    public function countQuantidade(int $idProduto): int;
    public function paginate(int $page = 1, int $perPage = 15): LengthAwarePaginator;
    public function QuantidadeEstoque(): Collection;
}
