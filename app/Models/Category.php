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

    protected $fillable = ['name', 'ccode', 'category_key', 'sort_order', 'slug', 'description', 'parent_id', 'is_deleted'];

    /**
     * Get the immediate parent of this category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the immediate sub-categories (children).
     */
    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Recursive relationship to get ALL descendants (children of children).
     */
    public function allChildren()
    {
        return $this->subCategories()->with('allChildren');
    }

    /**
     * Scope to only get top-level categories.
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

}