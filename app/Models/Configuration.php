<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property varchar $config_key config key
 * @property int $service_id Service ID
 * @property varchar $label label
 * @property varchar $value value
 * @property varchar $value_unit Value Unit
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
    protected $fillable = ['config_key',
        'service_id',
        'label',
        'value',
        'value_unit',
        'is_deleted',
        'description'];

    /**
     * Date time columns.
     */
    protected $dates = [];


}