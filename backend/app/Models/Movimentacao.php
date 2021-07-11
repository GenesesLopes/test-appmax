<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    use HasFactory;

    protected $fillable = ['id','produto_id','quantidade', 'acao', 'origem'];

    protected $table = 'movimentacoes';

    protected $casts = [
        'quantidade'=> 'integer',
        'produto_id' => 'integer'
    ];
}
