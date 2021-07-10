<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    use HasFactory;

    protected $fillable = ['id','produtos_id','quantidade', 'acao', 'origem'];

    protected $casts = [
        'quantidade'=> 'integer',
        'produtos_id' => 'integer'
    ];
}
