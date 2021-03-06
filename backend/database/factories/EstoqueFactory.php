<?php

namespace Database\Factories;

use App\Models\Estoque;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstoqueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Estoque::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'produto_id' => md5(uniqid()),
            'quantidade' => rand(1, 9),
            'acao' => rand(0, 2) % 2 === 0 ? 'Adição' : 'Remoção',
            'origem' => rand(0, 2) % 2 === 0 ? 'Sistema' : 'Api' 
        ];
    }
}
