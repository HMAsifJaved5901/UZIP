<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $employee_id employee id
@property int $service_id service id
@property float $wage_rate wage rate
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
'service_id',
'wage_id',
'is_deleted'];

    /**
    * Date time columns.
    */
    protected $dates=[];




}