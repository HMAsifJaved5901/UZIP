<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'dob',
        'address',
        'wage_rate',
        'commission_rate',
        'ssn',
        'email',
        'password',
        'company_id',
        'station_id',
        'country_id',
        'default_role_id',
        'login_web',
        'login_mobile',
        'contact_no',
        'is_deleted'
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
    protected function casts()
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

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function Role()
    {
        return $this->belongsTo(Role::class, 'default_role_id');
    }

    public function employeeServices()
    {
        return $this->hasMany(EmployeeService::class, 'employee_id', 'id');
    }

    public function payrollMethods()
    {
        return $this->hasMany(EmployeePayrollMethod::class, 'user_id', 'id');
    }

    public function employeeStation()
    {
        return $this->hasMany(EmployeeService::class, 'employee_id', 'id');
    }
}
