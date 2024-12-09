<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int unsigned $company_code company code
 * @property int $parent_id parent Company
 * @property varchar $company_name company name
 * @property varchar $company_address company address
 * @property varchar $company_logo company logo
 * @property tinyint $is_active is active
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 */
class Company extends Model
{

    /**
     * Database table name
     */
    protected $table = 'companies';

    /**
     * Mass assignable columns
     */
    protected $fillable = [
        'parent_id',
        'business_id',
        'company_code',
        'company_name',
        'company_address',
        'company_logo',
        'is_active',
        'is_deleted'
        ];

    /**
     * Date time columns.
     */
    protected $dates = [];


}