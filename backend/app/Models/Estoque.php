<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estoque extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id','produtos_id','quantidade'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'quantidade'=> 'integer',
        'produtos_id' => 'integer'
    ];
}
