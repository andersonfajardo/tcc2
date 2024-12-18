<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    protected $table = 'data';
    protected $fillable = ['id_indicator', 'datavalue', 'data_type_id'];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'id_indicator');
    }

    public function dataType()
    {
        return $this->belongsTo(DataType::class, 'data_type_id');
    }
}
