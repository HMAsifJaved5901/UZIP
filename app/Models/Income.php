<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @property varchar $invoice_no invoice no
 * @property date $income_date income date
 * @property int $station_id station id
 * @property int $service_id service id
 * @property string $category_id category code
 * @property varchar $sl_quantity quantity
 * @property varchar $sl_si_unit si unit
 * @property decimal $fuel_sales fuel sales
 * @property decimal $fuel_discount fuel discount
 * @property decimal $total_fuel_sale total fuel sale
 * @property decimal $other_sale other sale
 * @property decimal $other_discount other discount
 * @property decimal $total_other_sale total other sale
 * @property decimal $net_tax net tax
 * @property decimal $credit_card_sale credit card sale
 * @property decimal $cash_sale cash sale
 * @property decimal $net_sale net sale
 * @property decimal $amount amount
 * @property decimal $carwash_amount carWash amount
 * @property varchar $image_file image file
 * @property varchar $description description
 * @property int $is_deleted is deleted
 * @property int $status status
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
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
    protected $fillable = ['invoice_no',
        'income_date',
        'station_id',
        'station_code',
        'service_id',
        'service_code',
        'category_id',
        'sl_quantity',
        'sl_si_unit',
        'fuel_sales',
        'fuel_discount',
        'total_fuel_sale',
        'other_sale',
        'other_discount',
        'total_other_sale',
        'net_tax',
        'credit_card_sale',
        'cash_sale',
        'net_sale',
        'carwash_amount',
        'amount',
        'image_file',
        'description',
        'status',
        'is_deleted',
        'created_by',
        'updated_by',
        'is_adjustment',
        'is_paperwork',
        'cash_register_id',
        'transaction_adjustment_id',
        'paperwork_exemption_reason',
        'rejected_reason'
    ];

    /**
     * Date time columns.
     */
    protected $dates = ['income_date'];

    protected $casts = [
        'is_adjustment' => 'integer',
        'is_paperwork' => 'integer',
        'amount' => 'decimal:2',
        'sl_quantity' => 'decimal:2',
        'other_sale' => 'decimal:2',
        'cash_sale' => 'decimal:2',
        'total_other_sale' => 'decimal:2',
        'fuel_sales' => 'decimal:2',
        'total_fuel_sale' => 'decimal:2',
        'net_sale' => 'decimal:2',

    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function toArray()
    {
        return array_filter(parent::toArray(), function ($value) {
            return !is_null($value);
        });
    }

    public function images()
    {
        return $this->hasMany(IncomeImage::class, 'income_id');
    }

    public static function boot()
    {
        parent::boot();

        // Triggered when a new record is being created
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        // Triggered when an existing record is being updated
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    public static function getAmountByOriginalId($id)
    {
        return self::find($id, ['amount']);
    }


}