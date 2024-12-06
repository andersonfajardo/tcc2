<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPlan extends Model
{
    use HasFactory;

    protected $table = 'actionplan';
    protected $fillable = ['what', 'why', 'who', 'when', 'where', 'how', 'howmuch', 'id_kpi'];

    public function kpi()
    {
        return $this->belongsTo(KPI::class, 'id_kpi');
    }
}
