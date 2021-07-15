<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Utils\Information;
use App\Exceptions\Utils\InsufficientQuantity;
use App\Models\Estoque;
use App\Repositories\Contracts\IEstoque;
use App\Services\Contracts\IEstoqueServices;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
            throw new Information("É necessário inserir informações de metodo e httpPost", 422);
        $method = strtoupper($data['method']);
        // Validação de origem da requisição
        $data['httpHost'] == env('APP_URL_FRONT') ? $data['origem'] = 'sistema' : $data['origem'] = 'API';
        // Validação da ação
        $method == 'POST' ? $data['acao'] = 'Adição' : $data['acao'] = 'Remoção';
        $data = collect($data)->except(['httpHost', 'method'])->toArray();
        /** @var Estoque */
        $estoque = new Estoque($data);
        //Validação de estoque negativo
        if ($data['acao'] == 'Remoção' && $this->iEstoque->countQuantidadeProduto((int)$data['produto_id']) - (int)$data['quantidade'] < 0) {

            throw new InsufficientQuantity("Quantidade a ser removida deve ser igual ou superior à quantidade em estoque", 422);
        }
        $estoque->quantidade = $data['quantidade'];
        return $this->iEstoque->persistence($estoque);
    }

    public function relatorio(array $data): array
    {
        if (!\Arr::has($data, [
            'start_date',
            'end_date'
        ]))
            throw new Information("É necessário inserir informações de start_date e end_date", 422);

        $data['start_date'] = $data['start_date'] . ' 00:00:00';
        $data['end_date'] = $data['end_date'] . ' 23:59:00';
        /** @var Collection */
        $relatorio = $this->iEstoque->relatorioMovimentos($data);
        $dataReturn = $relatorio->reduce(function ($data, $movimentos) {
            $date = date('Y-m-d', strtotime($movimentos->updated_at));
            if (!\Arr::has($data, $date))
                $data[$date] = [];
            array_push($data[$date], $movimentos);
            return $data;
        }, []);
        return $dataReturn;
    }

    public function listagem(int $page, int $perPage = 15): LengthAwarePaginator
    {
        $page <= 0
            ? $page = 0
            : $page--;
        $queryData = $this->iEstoque->paginate($page * $perPage, $perPage);
        $response = collect($queryData->get('itens'))->reduce(function ($data, $queryData) {
            $key = collect($data['itens'])->map(function ($value, $key) use ($queryData) {
                if ($value['id'] == $queryData->id)
                    return $key;
            })->whereNotNull()->first();
            if (is_null($key)) {
                array_push($data['itens'], [
                    'id' => $queryData->id,
                    'nome' => $queryData->nome,
                    'sku' => $queryData->sku,
                    'total_estoque' => (int) $queryData->total_somado,
                ]);
            } else {
                $queryData->acao == 'Adição'
                    ? $data['itens'][$key]['total_estoque'] += (int)$queryData->total_somado
                    : $data['itens'][$key]['total_estoque'] -= (int) $queryData->total_somado;
            }
            return $data;
        }, [
            'itens' => []
        ]);
        $queryData = $queryData->merge([
            'itens' => $response['itens']
        ]);
       return new LengthAwarePaginator(
            $queryData->get('itens'),
            $queryData->get('total'),
            $perPage,
            $page + 1,
            ['path' => route('estoque.index')]
        );
    }

    public function quantidadeBaixa()
    {
        $queryData = $this->iEstoque->QuantidadeEstoqueBaixa();
        $response = $queryData->reduce(function ($data, $queryData) {
            $key = collect($data)->map(function ($value, $key) use ($queryData) {
                if ($value['id'] == $queryData->id)
                    return $key;
            })->whereNotNull()->first();
            if (is_null($key)) {
                array_push($data, [
                    'id' => $queryData->id,
                    'nome' => $queryData->nome,
                    'sku' => $queryData->sku,
                    'total_estoque' => (int) $queryData->total_somado,
                ]);
            } else {
                $queryData->acao == 'Adição'
                    ? $data[$key]['total_estoque'] += (int)$queryData->total_somado
                    : $data[$key]['total_estoque'] -= (int) $queryData->total_somado;
            }
            return $data;
        }, []);
        return collect($response)->filter(fn($value) => $value['total_estoque'] < 100);
    }
}
