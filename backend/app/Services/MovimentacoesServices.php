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
            $date = date('Y-m-d',strtotime($movimentos->updated_at));
            if(!\Arr::has($data,$date))
                $data[$date] = [];
            array_push($data[$date],$movimentos);
            return $data;
        },[]);
        return $dataReturn;
    }
}
