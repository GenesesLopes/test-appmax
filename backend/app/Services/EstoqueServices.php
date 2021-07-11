<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use App\Services\Contracts\IEstoqueServices;
use Exception;

class EstoqueServices implements IEstoqueServices
{

    public function __construct(
        private IEstoque $iEstoque
    ) {
    }

    public function movimentacao(array $data): Estoque
    {
        if (!\Arr::has($data, [
            'method',
            'httpHost'
        ]))
            throw new Exception("É necessário inserir informações de metodo e httpPost");
        $method = strtoupper($data['method']);
        $data['httpHost'] == env('APP_URL_FRONT') ? $data['origem'] = 'sistema' : $data['origem'] = 'API';
        
        if($method == 'POST'){
            $data['acao'] = 'Adicao';
            return $this->iEstoque->add($data);
        }
        $data['acao'] = 'Remocao';
        return $this->iEstoque->remove($data);
    }
}
