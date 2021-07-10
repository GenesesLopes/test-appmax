<?php
declare (strict_types = 1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IProduto {

    public function find(int $int): ?Model;

    public function findBySku(string $sku): ?Model;

    public function findAll(): Collection;

    public function paginate(int $page = 1, int $perPage = 15): LengthAwarePaginator;

    public function save(array $data, ?int $id = null): Model;

    public function destroy(int $id, bool $force = false): ?bool;

}
