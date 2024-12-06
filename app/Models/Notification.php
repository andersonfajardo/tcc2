<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications'; // Certifique-se de que essa tabela existe
    protected $fillable = ['user_id', 'type', 'threshold', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

