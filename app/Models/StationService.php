<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $station_id station
 * @property int $service_id service
 * @property int $is_dealer is dealer
 * @property int $is_commission is commission
 * @property int $supplier_id supplier
 * @property varchar $pos_id pos
 * @property varchar $restaurant_pos_id restaurant pos
 * @property varchar $car_wash_operator_id car wash operator
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 */
class StationService extends Model
{

    /**
     * Database table name
     */
    protected $table = 'station_services';

    /**
     * Mass assignable columns
     */
    protected $fillable = ['station_id',
        'service_id',
        'is_dealer',
        'is_commission',
        'supplier_id',
        'restaurant_pos_id',
        'pos_id',
        'car_wash_operator_id',
        'is_active'];

    /**
     * Date time columns.
     */
    protected $dates = [];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
