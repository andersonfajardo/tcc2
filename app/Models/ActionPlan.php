<?php

namespace App\Models;
use App\Models\Indicator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPlan extends Model
{
    use HasFactory;

    protected $table = 'actionplan';
    protected $fillable = ['what', 'why', 'who', 'when', 'where', 'how', 'howmuch', 'id_indicator', 'enable'];

    public function kpi()
    {
        return $this->belongsTo(KPI::class, 'id_kpi');
    }

    public function indicator()
{
    return $this->belongsTo(Indicator::class, 'id_indicator');
}
}
