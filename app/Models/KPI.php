<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KPI extends Model
{
    use HasFactory;

    protected $table = 'kpi';
    protected $fillable = ['user_id', 'okr_id', 'kpititle', 'kpidescription', 'kpistargetvalue', 'startdate', 'enddate', 'createdate', 'indicator_type'];

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

    
}
