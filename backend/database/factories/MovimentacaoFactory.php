<?php

namespace Database\Factories;

use App\Models\Movimentacao;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovimentacaoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Movimentacao::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        do{
            $quantidade = rand(-2, 9);
        }while($quantidade == 0);
        
        return [
            'estoque_produto_id' => rand(1,10),
            'quantidade' => $quantidade,
            'acao' => $quantidade > 0 ? 'Adição' : 'Remoção',
            'origem' => rand(0, 2) % 2 === 0 ? 'Sistema' : 'Api' 
        ];
    }
}
