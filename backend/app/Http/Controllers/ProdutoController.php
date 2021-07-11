<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Models\Produto;
use App\Repositories\Contracts\IProduto;

class ProdutoController extends Controller
{
    public function __construct(
        private IProduto $iProduto
    ){
        
    }
    public function index(ProdutoRequest $request)
    {
        $perPage = $request->query('per_page') !== null ? (int)$request->query('per_page') : 15;
        $page = $request->query('page') !== null ? (int) $request->query('page'): 1;
        return $this->iProduto->paginate(perPage: $perPage, page: $page);
    }

    
    public function store(ProdutoRequest $request)
    {
        return $this->iProduto->save($request->all());
    }

    
    public function show(int $id)
    {
        return $this->iProduto->find($id);
    }

    public function update(ProdutoRequest $request, int $id)
    {
        return $this->iProduto->save($request->all(),$id);
    }

   
    public function destroy(ProdutoRequest $request)
    {
        $this->iProduto->destroy($request->id);
        return response()->noContent();
    }
}
