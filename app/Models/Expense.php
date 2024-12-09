<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property date $expense_date expense date
@property int $station_id station id
@property int $category_id category id
@property decimal $amount amount
@property varchar $image_file image file
@property varchar $invoice_no invoice no
@property varchar $description description
@property timestamp $created_at created at
@property timestamp $updated_at updated at
   
 */
class Expense extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'expense';

    /**
    * Mass assignable columns
    */
    protected $fillable=['expense_date',
'invoice_no',
'station_id',
'service_id',
'category_id',
'amount',
'image_file',
'description',
'is_deleted'];

    /**
    * Date time columns.
    */
    protected $dates=['expense_date'];




}