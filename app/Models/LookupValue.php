<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $type type
@property varchar $value value
@property text $description description
@property timestamp $created_at created at
@property timestamp $updated_at updated at
   
 */
class LookupValue extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'lookup_values';

    /**
    * Mass assignable columns
    */
    protected $fillable=['type',
'value',
'description'];

    /**
    * Date time columns.
    */
    protected $dates=[];




}