<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $employee_id employee id
@property int $service_id service id
@property int $station_id station id
@property float $wage_rate wage rate
@property float $commission_rate commission rate
@property tinyint $is_deleted is deleted
   
 */
class EmployeeService extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'employee_services';

    /**
    * Mass assignable columns
    */
    protected $fillable=['is_deleted',
'employee_id',
'station_id',
'service_id',
'wage_rate',
'commission_rate',
'is_deleted'];

    /**
    * Date time columns.
    */
    protected $dates=[];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id');
    }


}