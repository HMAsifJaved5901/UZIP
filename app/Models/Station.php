<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property int $category_id category id
@property varchar $name name
@property varchar $code code
@property int $company_id company id
@property int $manager_id manager id
@property varchar $location location
@property decimal $latitude latitude
@property decimal $longitude longitude
@property varchar $phone phone
@property varchar $opening_hours opening hours
@property int $is_active is active
@property tinyint $is_deleted is deleted
@property timestamp $created_at created at
@property timestamp $updated_at updated at
   
 */
class Station extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'stations';

    /**
    * Mass assignable columns
    */
    protected $fillable=['category_id',
'name',
'code',
'company_id',
'manager_id',
'location',
'latitude',
'longitude',
'phone',
'opening_hours',
'is_active',
'is_deleted'];

    /**
    * Date time columns.
    */
    protected $dates=[];




}