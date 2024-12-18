<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ActionPlan;
use App\Models\Indicator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'user';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function actionPlans()
    {
    return $this->hasMany(ActionPlan::class);
    }

    public function actionPlansPerId()
{
    return $this->hasMany(ActionPlan::class, 'id_indicator'); // 'id_indicator' é a chave estrangeira
}

    public function getUserIndicators(){
        return $this->hasMany(Indicator::class)->where('enable',1);
    }

    public function getUserIndicatorsDash(){
        return $this->hasMany(Indicator::class)->where('enable',1)->where("dashboard", 1)->with("type");
    }

    //public function getUserActionPlan(){
    //    return $this->hasMany(ActionPlan::class)->where('id_indicator', $this->id);
    //}

    public function getUserActionPlan()
    {
        return $this->actionPlansPerUser2()->with('indicator')->get(); // Retorna os planos de ação associados ao usuário
    }

    public function indicators()
    {
        return $this->hasMany(Indicator::class);
    }

    public function actionPlansPerUser2()
    {
        return $this->hasManyThrough(
            ActionPlan::class,      // Modelo final que queremos alcançar
            Indicator::class,       // Modelo intermediário
            'user_id',              // Chave estrangeira no modelo intermediário (Indicator)
            'id_indicator',         // Chave estrangeira no modelo final (ActionPlan)
            'id',                   // Chave local no modelo User
            'id'                    // Chave local no modelo Indicator
        )->where('actionplan.enable',1);
    }
}
