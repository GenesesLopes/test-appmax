<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Utils\Information;
use App\Exceptions\Utils\InsufficientQuantity;
use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use App\Services\Contracts\IEstoqueServices;

class EstoqueServices implements IEstoqueServices
{

    public function __construct(
        private IEstoque $iEstoque
    ) {
    }

    public function estoque(array $data): Estoque
    {
        if (!\Arr::has($data, [
            'method',
            'httpHost'
        ]))
            throw new Information("É necessário inserir informações de metodo e httpPost");
        $method = strtoupper($data['method']);
        // Validação de origem da requisição
        $data['httpHost'] == env('APP_URL_FRONT') ? $data['origem'] = 'sistema' : $data['origem'] = 'API';
        // Validação da ação
        $method == 'POST' ? $data['acao'] = 'Adição' : $data['acao'] = 'Remoção';
        /** @var Estoque */
        $estoque = new Estoque($data);
        //Validação de estoque negativo
        if ($data['acao'] == 'Remoção' && $this->iEstoque->countQuantidade($data['produto_id']) - $data['quantidade'] < 0) {
            throw new InsufficientQuantity("Quantidade a ser removida deve ser igual ou superior à quantidade em estoque", 422);
        }
        $estoque->quantidade = $data['quantidade'];
        return $this->iEstoque->persistence($estoque);
    }
}
