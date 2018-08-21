<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Merchant extends Model
{
    use Notifiable;

    const COLUMN_ID = 'id';
    const COLUMN_NAME= 'name';
    const COLUMN_CONTRACT_START= 'contract_start';
    const COLUMN_CONTRACT_END= 'contract_end';
    const COLUMN_STATUS= 'status';
    const TABLE = 'merchants';

    const ID = self::TABLE.'.'.self::COLUMN_ID;
    const NAME=  self::TABLE.'.'.self::COLUMN_NAME;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME, self::COLUMN_CONTRACT_START, self::COLUMN_CONTRACT_END,self::COLUMN_STATUS
    ];

    public static $rules = [
        self::COLUMN_NAME => 'required|max:255',
        self::COLUMN_CONTRACT_START => 'required|date',
        self::COLUMN_CONTRACT_END => 'required|date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staff(){
        return $this->hasMany(Staff::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buses(){
        return $this->hasMany(Bus::class,Bus::COLUMN_MERCHANT_ID,self::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function schedules(){
        return $this->hasManyThrough(Schedule::class,Bus::class, Bus::COLUMN_MERCHANT_ID, Schedule::COLUMN_BUS_ID,Merchant::COLUMN_ID,Bus::COLUMN_ID);
    }

    /**
     * @return array
     */
    public static function getMerchantsArray(): array
    {
        $merchants = Merchant::all();
        $merchantArray = array(0 => __('admin_pages.page_bookings_form_fields_select_merchant'));
        foreach ($merchants as $merchant) {
            $merchantArray[$merchant->id] = $merchant->name;
        }
        return $merchantArray;
    }

    /**
     * @param $staff
     * @return array
     */
    public static function merchantBusesArray($staff): array
    {
        $merchant = $staff->merchant()->with('buses:id,reg_number,merchant_id')->first();
        $buses = array(0 => 'Select bus');
        foreach ($merchant->buses as $bus) {
            $buses[$bus->id] = $bus->reg_number;
        }
        return $buses;
    }

    /**
     * @param $array
     * @return mixed
     */
    public static function getMerchantSelectArray($array){

        $merchants = Merchant::all();

        foreach ($merchants as $merchant) {
            $array[$merchant->id] = $merchant->name;
        }

        return $array;
    }

    /**
     * @return array
     */
    public function getMerchantBusArray(): array
    {
        $busArray = array(0 => 'Select bus');

        $buses = $this->buses()->select(['id','reg_number'])->get();

        foreach ($buses as $bus) {
            $busArray[$bus->id] = $bus->reg_number;
        }
        return $busArray;
    }

    /**
     * Scope a query to get expired models.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeContractExpired($query)
    {
        return $query->whereDate(self::COLUMN_CONTRACT_END, '<', date('Y-m-d'));
    }

    /**
     * Scope a query to get expired models.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeContractActive($query)
    {
        return $query->whereDate(self::COLUMN_CONTRACT_END, '>=', date('Y-m-d'));
    }

}
