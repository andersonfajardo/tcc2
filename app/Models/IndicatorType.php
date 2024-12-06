<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorType extends Model
{
    use HasFactory;

    protected $table = 'indicatortype';
    protected $fillable = ['indicatordescription'];

    public function kpis()
    {
        return $this->hasMany(KPI::class, 'indicator_type');
    }
}
