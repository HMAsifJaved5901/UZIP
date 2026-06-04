<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $reference_type reference type
   @property int $reference_value reference value
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
    protected $fillable=[
'reference_type',
'reference_value',
'type',
'value',
'description'];

    /**
    * Date time columns.
    */
    protected $dates=[];


    public function lookupVendor()
    {
        return $this->hasMany(VendorLookupValue::class, 'lookup_id', 'id');
    }

}