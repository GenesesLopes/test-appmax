<?php

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ProdutoSeeder::class,
            EstoqueSeeder::class
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
