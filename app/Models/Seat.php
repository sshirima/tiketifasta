<?php

namespace App\Models;

use App\Repositories\Merchant\SeatRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Seat extends Model
{

    const COLUMN_ID = 'id';
    const COLUMN_SEAT_NAME = 'seat_name';
    const COLUMN_BUS_ID = 'bus_id';
    const COLUMN_TYPE = 'type';
    const COLUMN_STATUS = 'status';

    const TABLE = 'seats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_SEAT_NAME,self::COLUMN_BUS_ID,self::COLUMN_TYPE,self::COLUMN_STATUS
    ];

    public static $rules = [
        self::COLUMN_SEAT_NAME=>'required',
        self::COLUMN_BUS_ID => 'required',
        self::COLUMN_TYPE => 'required'
    ];

    public $timestamps = false;

    public function bus(){
        return $this->belongsTo(Bus::class,Seat::COLUMN_BUS_ID,Bus::COLUMN_ID);
    }

    public static function seatArrangementArray($arrangement){
        $seatArray = array();
        if(!empty($arrangement)){
            $length = strlen($arrangement);
            $rowIndex = 1;
            $seatRowIndex = 1;
            $seatIndex = 0;
            $seatNumber = 1;
            for ($i = 0; $i <= $length; $i++) {
                $char = substr($arrangement, $i, 1);

                if (strcmp($char, ',') == 0) {
                    $rowIndex++;
                    $seatRowIndex=1;
                }
                if (strcmp($char, '_') == 0) {
                    $seatIndex++;
                    $seatRowIndex++;
                }

                if (strcmp($char, 'e') == 0) {
                    $name = Seat::numberToRowLetter($rowIndex).$seatRowIndex;
                    $seatArray[$name]['index'] = $seatNumber;
                    $seatArray[$name]['column'] = $seatRowIndex;
                    $seatArray[$name]['row'] = $rowIndex;
                    $seatNumber++;
                    $seatIndex++;
                    $seatRowIndex++;
                }
                if (strcmp($char, 'f') == 0) {
                    $name = Seat::numberToRowLetter($rowIndex).$seatRowIndex;
                    $seatArray[$name]['index'] = $seatNumber;
                    $seatArray[$name]['column'] = $seatRowIndex;
                    $seatArray[$name]['row'] = $rowIndex;
                    $seatNumber++;
                    $seatIndex++;
                    $seatRowIndex++;
                }
                continue;
            }
        }
        return $seatArray;
    }

    public static function createBusSeats($bus_id, $bustype_id, SeatRepository $seatRepository){

        $bus_type = DB::table(Bustype::TABLE)->select([Bustype::COLUMN_ARRANGEMENT])->find($bustype_id);
        $seats = array();
        if(!empty($bus_type)){
            $arrangement = $bus_type->seat_arrangement;
            $length = strlen($arrangement);
            $rowIndex = 1;
            $seatRowIndex = 1;
            $seatIndex = 0;
            for ($i = 0; $i <= $length; $i++) {
                $char = substr($arrangement, $i, 1);
                if (strcmp($char, ',') == 0) {
                    $rowIndex++;
                    $seatRowIndex=1;
                }
                if (strcmp($char, '_') == 0) {
                    $seatIndex++;
                    $seatRowIndex++;
                }
                if (strcmp($char, 'e') == 0) {
                    $seat_param = ['seat_name'=>Seat::numberToRowLetter($rowIndex).$seatRowIndex,'bus_id'=>$bus_id, 'type'=>'Economy'];
                    $seats[$seatIndex] = $seatRepository->create($seat_param);
                    $seatIndex++;
                    $seatRowIndex++;
                }
                if (strcmp($char, 'f') == 0) {
                    $seat_param = ['seat_name'=>Seat::numberToRowLetter($rowIndex).$seatRowIndex,'bus_id'=>$bus_id,'type'=>'First'];
                    $seats[$seatIndex] = $seatRepository->create($seat_param);
                    $seatIndex++;
                    $seatRowIndex++;
                }
                continue;
            }
        }
    }

    public static function numberToRowLetter($row){
        $letter= '';
        switch ($row){
            case 1:
                $letter = 'A';
                break;
            case 2:
                $letter = 'B';
                break;
            case 3:
                $letter = 'C';
                break;
            case 4:
                $letter = 'D';
                break;
            case 5:
                $letter = 'E';
                break;
            case 6:
                $letter = 'F';
                break;
            case 7:
                $letter = 'G';
                break;
            case 8:
                $letter = 'H';
                break;
            case 9:
                $letter = 'J';
                break;
            case 10:
                $letter = 'K';
                break;
            case 11:
                $letter = 'L';
                break;
            case 12:
                $letter = 'M';
                break;
            case 13:
                $letter = 'N';
                break;
            case 14:
                $letter = 'P';
                break;
            case 15:
                $letter = 'Q';
                break;
            case 16:
                $letter = 'R';
                break;
            case 17:
                $letter = 'S';
                break;
            case 18:
                $letter = 'T';
                break;
            case 19:
                $letter = 'U';
                break;
            case 20:
                $letter = 'V';
                break;
            case 21:
                $letter = 'W';
                break;
            case 22:
                $letter = 'X';
                break;
            case 23:
                $letter = 'Y';
                break;
            case 24:
                $letter = 'Z';
                break;
        }
        return $letter;
    }
}
