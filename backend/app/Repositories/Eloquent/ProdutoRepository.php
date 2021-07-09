<?php
declare (strict_types = 1);

namespace App\Repositories\Eloquent;

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
            if(!is_numeric($value))
                $value = preg_replace("/\W/","",$value);
            $data[$key] = $value == '' ? null: $value;
        }
        
        return Produto::updateOrCreate(
            ['id' => $id],
            $data
        );
    }

}