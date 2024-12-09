<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $name name
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
'description',
'category_id',
'is_active',
'is_deleted'];

    /**
    * Date time columns.
    */
    protected $dates=[];




}