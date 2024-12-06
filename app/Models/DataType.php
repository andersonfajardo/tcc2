<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataType extends Model
{
    use HasFactory;

    protected $table = 'datatype';
    protected $fillable = ['datatypedescription'];

    public function data()
    {
        return $this->hasMany(Data::class);
    }
}
