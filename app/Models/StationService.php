<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $station_id station id
@property int $service_id service id
@property timestamp $created_at created at
@property timestamp $updated_at updated at
   
 */
class StationService extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'station_services';

    /**
    * Mass assignable columns
    */
    protected $fillable=['station_id',
'service_id',
'is_active'];

    /**
    * Date time columns.
    */
    protected $dates=[];




}
