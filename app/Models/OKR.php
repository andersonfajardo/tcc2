<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OKR extends Model
{
    use HasFactory;

    protected $table = 'okr';
    protected $fillable = ['user_id', 'okrtitle', 'okrdescription', 'startdate', 'enddate', 'createdate'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kpis()
    {
        return $this->hasMany(KPI::class, 'okr_id');
    }
}