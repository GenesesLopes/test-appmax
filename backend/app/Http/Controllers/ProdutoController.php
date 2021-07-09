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
    public function index(int $perPage = 15)
    {
        return $this->iProduto->paginate($perPage);
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
