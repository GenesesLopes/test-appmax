<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Exceptions\Sql\Nullable;
use App\Exceptions\Sql\Unique;
use App\Models\Produto;
use App\Repositories\Contracts\IProduto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ProdutoRepository implements IProduto
{

    public function find(int $id): ?Model
    {
        return Produto::find($id);
    }

    public function findBySku(string $sku): ?Model
    {
        return Produto::where('sku', $sku)->first();
    }

    public function findAll(): Collection
    {
        return Produto::all();
    }

    public function paginate(int $page = 1, int $perPage = 15): LengthAwarePaginator
    {
        return Produto::paginate($perPage, page: $page);
    }

    public function save(array $data, ?int $id = null): Model
    {
        foreach ($data as $key => $value) {
            if (!is_null($value)) {
                if (!is_numeric($value) && !is_bool($value))
                    $value = preg_replace("/\W/", "", $value);
                $data[$key] = $value == '' || is_bool($value) ? null : $value;
            }
        }
        try {
            return Produto::updateOrCreate(
                ['id' => $id],
                $data
            );
        } catch (\PDOException $pdoE) {
            $code = $pdoE->errorInfo[1];
            match ($code) {
                1048 => throw new Nullable($pdoE->errorInfo[2], 422),
                1062 => throw new Unique($pdoE->errorInfo[2], 422)
            };
        }
    }
}
