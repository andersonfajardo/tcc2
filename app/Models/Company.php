<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';
    protected $fillable = ['name', 'adress', 'contactmail', 'contactphone', 'createdate'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}