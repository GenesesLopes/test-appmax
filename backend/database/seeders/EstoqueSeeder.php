<?php

namespace Database\Seeders;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class EstoqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estoque::factory()
            ->count(3)
            ->state(new Sequence([
                'quantidade' => rand(1, 3),
                'acao' =>  'Adição',
                'origem' => rand(0, 2) % 2 === 0 ? "Sistema" : 'api'
            ]))
            ->for(Produto::factory()->create())
            ->create();
    }
}
