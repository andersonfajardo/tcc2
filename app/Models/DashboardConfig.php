<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardConfig extends Model
{
    use HasFactory;

    protected $table = 'dashboardconfig';
    protected $fillable = ['user_id', 'widget', 'type_indicator', 'positionx', 'positiony'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
