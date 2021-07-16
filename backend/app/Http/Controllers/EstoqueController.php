<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstoqueRequest;
use App\Http\Requests\RelatorioRequest;
use App\Repositories\Contracts\IEstoque;
use App\Services\Contracts\IEstoqueServices;


class EstoqueController extends Controller
{
    public function __construct(
        private IEstoqueServices $estoqueServices,
        private IEstoque $estoqueRepository
    ) {
    }

    public function index()
    {
        return $this->estoqueServices->listagem();
    }

    public function store(EstoqueRequest $request)
    {
        $request = $request->merge([
            'httpHost' => $request->getHttpHost(),
            'method' => $request->getMethod()
        ]);
        $response = $this->estoqueServices->estoque($request->all());

        return response()->json($response->toArray());
    }

    public function update(EstoqueRequest $request)
    {
        $request = $request->merge([
            'httpHost' => $request->getHttpHost(),
            'method' => $request->getMethod()
        ]);

        $response = $this->estoqueServices->estoque($request->all());

        return response()->json($response->toArray());
    }

    public function relatorio(RelatorioRequest $request)
    {
        return $this->estoqueServices->relatorio($request->all());
    }

    public function baixoEstoque()
    {
        return $this->estoqueServices->quantidadeBaixa();
    }
}
