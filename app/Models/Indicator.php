<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;

    protected $table = 'indicator'; // Nome da tabela
    protected $attributes = [
        'enable' => 1,
        'dashboard' => 0,
    ];
    protected $fillable = [
        'user_id',
        'kpititle',
        'kpidescription',
        'startdate',
        'createdate',
        'indicator_type',
        'okr',
        'enable',
        'dashboard',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function okr()
    {
        return $this->belongsTo(OKR::class, 'okr_id');
    }

    public function indicatorType()
    {
        return $this->belongsTo(IndicatorType::class, 'indicator_type');
    }

    public function data()
    {
        return $this->hasMany(Data::class, 'id_indicator');
    }

    public function actionPlans()
    {
        return $this->hasMany(ActionPlan::class);

    }
    public $timestamps = false; // Desabilitando timestamps padrão

    
}
