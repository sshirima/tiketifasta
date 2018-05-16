<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/24/2018
 * Time: 9:41 PM
 */

namespace App\Http\Controllers\Merchants;


class TicketController extends BaseController
{
    public function __construct(SeatRepository $seatRepository, BusRepository $busRepository)
    {
        $this->middleware('auth:merchant');
        $this->seatRepository = $seatRepository;
        $this->busRepo = $busRepository;
    }

    public function index($bus_id){

    }

    public function create($bus_id){

    }

    public function edit($bus_id, $ticket_id){}

    public function update(){}

    public function delete($bus_id, $ticket_id){}

    public function remove($bus_id, $ticket_id){}

}