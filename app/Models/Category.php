<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
   @property varchar $name name
@property varchar $slug slug
@property varchar $description description
@property int $parent_id parent id
@property int $is_deleted Deleted
@property timestamp $created_at created at
@property timestamp $updated_at updated at
   
 */
class Category extends Model 
{
    
    /**
    * Database table name
    */
    protected $table = 'categories';

    /**
    * Mass assignable columns
    */
    protected $fillable=['name',
'category_key',
'sort_order',
'slug',
'description',
'parent_id',
'is_deleted'
    ];

    /**
    * Date time columns.
    */
    protected $dates=[];




}