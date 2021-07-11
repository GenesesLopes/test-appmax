<?php
declare (strict_types = 1);

namespace App\Services;

use App\Repositories\Contracts\IMovimentacao;
use App\Services\Contracts\IMovimentacoesServices;
use Illuminate\Support\Collection;

class MovimentacoesServices implements IMovimentacoesServices
{
    private Collection $movimentos;

    public function __construct(
        private IMovimentacao $iMovimentacao
    )
    {
    }

    public function relatorio(array $data)
    {   
        $this->movimentos = $this->iMovimentacao->relatorioMovimentos($data);
        
        $dataReturn = $this->movimentos->reduce(function($data, $movimentos){ 
            if(!\Arr::has($data,$movimentos->sku))
                $data[$movimentos->sku] = [];
            array_push($data[$movimentos->sku],$movimentos);
            return $data;
        },[]);
        return $dataReturn;
    }
}
