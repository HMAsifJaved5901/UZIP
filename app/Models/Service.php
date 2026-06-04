<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $name name
@property varchar $scode scode
@property varchar $description description
@property int $category_id category id
@property int $is_active is active
@property int $is_deleted is Deleted
@property timestamp $created_at created at
@property timestamp $updated_at updated at
   
 */
class Service extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'services';

    /**
    * Mass assignable columns
    */
    protected $fillable=['name',
'scode',
'description',
'category_id',
'is_active',
'is_deleted'];

    /**
    * Date time columns.
    */
    protected $dates=[];

    public function stations()
    {
        return $this->belongsToMany(Station::class, 'station_services')
            ->withPivot('is_active', 'created_at', 'updated_at');
    }

    public function lookupValues()
    {
        return $this->hasMany(LookupValue::class, 'reference_value')
            ->where('reference_type', 'service');
    }

    public function serviceModels(){
        return $this->hasMany(ServiceModel::class);
    }
}