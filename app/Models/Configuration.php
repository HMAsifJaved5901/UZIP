<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property varchar $config_key config key
 * @property varchar $label label
 * @property varchar $value value
 * @property text $description description
 */
class Configuration extends Model
{

    /**
     * Database table name
     */
    protected $table = 'configurations';

    /**
     * Mass assignable columns
     */
    protected $fillable = ['description',
        'config_key',
        'label',
        'value',
        'is_deleted',
        'description'];

    /**
     * Date time columns.
     */
    protected $dates = [];


}