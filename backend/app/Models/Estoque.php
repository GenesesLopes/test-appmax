<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    protected $fillable = ['id','produto_id','quantidade', 'acao', 'origem'];

    protected $table = 'estoques';

    protected $casts = [
        'quantidade'=> 'integer',
        'produto_id' => 'integer'
    ];
    public function produto()
    {
        return $this->belongsTo(Produto::class,'produto_id');
    }
}
