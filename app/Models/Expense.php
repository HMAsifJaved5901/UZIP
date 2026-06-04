<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @property date $expense_date expense date
 * @property int $station_id station id
 * @property int $category_id category id
 * @property int $payment_type_id payment type id
 * @property int $vendor_id vendor id
 * @property int $status status
 * @property int $cih_id cih id
 * @property decimal $amount amount
 * @property varchar $exp_si_unit Exp SI unit
 * @property varchar $exp_quantity Exp Quantity
 * @property varchar $image_file image file
 * @property varchar $invoice_no invoice no
 * @property varchar $cih_source cih source
 * @property varchar $description description
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
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
    protected $fillable = ['expense_date',
        'invoice_no',
        'station_id',
        'station_code',
        'service_id',
        'service_code',
        'category_id',
        'payment_type_id',
        'vendor_id',
        'exp_quantity',
        'exp_si_unit',
        'amount',
        'image_file',
        'description',
        'cih_id',
        'cheque_no',
        'cih_source',
        'status',
        'is_adjustment',
        'rejected_reason',
        'cash_register_id',
        'transaction_adjustment_id',
        'created_by',
        'updated_by',
        'is_deleted'];

    /**
     * Date time columns.
     */
    protected $dates = ['expense_date'];
    protected $casts = [
        'is_adjustment' => 'integer',
        'exp_quantity' => 'decimal:2',
        'exp_si_unit' => 'decimal:2',
        'amount' => 'decimal:2'
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
        return $this->hasMany(ExpenseImage::class, 'expense_id');
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

    public static function getAmountByOriginalId($id){
        return self::find($id, ['amount']);
    }

    public function payrollPayment()
    {
        return $this->hasOne(EmployeeWagePayment::class, 'expense_id', 'id');
    }

}