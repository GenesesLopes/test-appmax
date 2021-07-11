<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstoqueRequest;
use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use App\Services\Contracts\IEstoqueServices;
use App\Services\EstoqueServices;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{


    public function __construct(
        private IEstoqueServices $estoqueServices,
        private IEstoque $estoqueRepository
    ) {
    }

    public function index(EstoqueRequest $request)
    {
        $perPage = $request->query('per_page') !== null ? (int)$request->query('per_page') : 15;
        $page = $request->query('page') !== null ? (int) $request->query('page'): 1;
        return $this->estoqueRepository->paginate($page, $perPage);
    }

    public function store(EstoqueRequest $request)
    {
        $request = $request->merge([
            'httpHost' => $request->getHttpHost(),
            'method' => $request->getMethod()
        ]);

        $response = $this->estoqueServices->movimentacao($request->all());

        return response()->json($response->toArray());
    }

    public function update(EstoqueRequest $request)
    {
        $request = $request->merge([
            'httpHost' => $request->getHttpHost(),
            'method' => $request->getMethod()
        ]);

        $response = $this->estoqueServices->movimentacao($request->all());

        return response()->json($response->toArray());
    }
}
