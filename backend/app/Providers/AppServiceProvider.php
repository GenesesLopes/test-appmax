<?php

namespace App\Providers;

use App\Repositories\Contracts\IEstoque;
use App\Repositories\Contracts\IMovimentacao;
use App\Repositories\Contracts\IProduto;
use App\Repositories\Eloquent\EstoqueRepository;
use App\Repositories\Eloquent\MovimentacaoRepository;
use App\Repositories\Eloquent\ProdutoRepository;
use App\Services\Contracts\IEstoqueServices;
use App\Services\EstoqueServices;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            IProduto::class,
            ProdutoRepository::class
        );

        $this->app->bind(
            IMovimentacao::class,
            MovimentacaoRepository::class
        );

        $this->app->bind(
            IEstoque::class,
            EstoqueRepository::class
        );

        $this->app->bind(
            IEstoqueServices::class,
            EstoqueServices::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
