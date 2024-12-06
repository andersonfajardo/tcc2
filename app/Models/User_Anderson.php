<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'user';
    protected $fillable = [
        'company_id',
        'name',
        'username',
        'passwordhash',
        'email',
        'datacreated',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'passwordhash',
        'remember_token',
    ];

    public $timestamps = false;

    /**
     * Define the relationships.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function okrs()
    {
        return $this->hasMany(OKR::class);
    }

    public function kpis()
    {
        return $this->hasMany(KPI::class);
    }

    public function dashboardConfigs()
    {
        return $this->hasMany(DashboardConfig::class);
    }

    public function apiTokens()
    {
        return $this->hasMany(APIToken::class);
    }
}
