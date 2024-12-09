<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int unsigned $company_code company code
 * @property varchar $name Country name
 * @property varchar $code Country Code
 * @property varchar $placeholder Country Calling Code
 * @property tinyint $status is active
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 */

class Country extends Model
{

    /**
     * Database table name
     */
    protected $table = 'countries';

    /**
     * Mass assignable columns
     */
    protected $fillable = ['name',
        'code',
        'placeholder',
        'status'];

    /**
     * Date time columns.
     */
    protected $dates = [];
}
