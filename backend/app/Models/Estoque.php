<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estoque extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['produto_id','quantidade'];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'quantidade'=> 'integer'
    ];

    protected $primaryKey = 'produto_id';

    public $incrementing = false;
}
