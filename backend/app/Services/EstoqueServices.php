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
        $method == 'POST' ? $data['acao'] = 'Adição' : $data['acao'] = 'Remoção';
        /** @var Estoque */
        $estoque = new Estoque($data);
        //Validação de estoque negativo
        if ($data['acao'] == 'Remoção' && $this->iEstoque->countQuantidade($data['produto_id']) - $data['quantidade'] < 0) {
            throw new Exception("Quantidade a ser removida deve ser igual ou superior à quantidade em estoque", 422);
        }
        $estoque->quantidade = $data['quantidade'];
        return $this->iEstoque->persistence($estoque);
    }
}
