<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property date $income_date income date
@property int $station_id station id
@property int $category_id category id
@property decimal $amount amount
@property varchar $image_file image file
@property varchar $invoice_no invoice no
@property varchar $description description
@property timestamp $created_at created at
@property timestamp $updated_at updated at
   
 */
class Income extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'income';

    /**
    * Mass assignable columns
    */
    protected $fillable=['income_date',
'station_id',
'invoice_no',
'service_id',
'category_id',
'amount',
'image_file',
'description'];

    /**
    * Date time columns.
    */
    protected $dates=['income_date'];




}