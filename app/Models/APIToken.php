<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class APIToken extends Model
{
    use HasFactory;

    protected $table = 'apitoken'; // Nome da tabela
    protected $fillable = ['user_id', 'token', 'expirationdate']; // Colunas permitidas

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
