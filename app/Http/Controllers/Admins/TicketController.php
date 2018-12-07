<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/11/2018
 * Time: 10:42 AM
 */

namespace App\Http\Controllers\Admins;


use App\Models\Ticket;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class TicketController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {

        $table = $this->createTicketsTable();

        return view('admins.pages.tickets.index')->with(['ticketsTable' => $table]);

    }

    /**
     * @return mixed
     */
    protected function createTicketsTable()
    {
        $table = app(TableList::class)
            ->setModel(Ticket::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.tickets.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('tickets.id as id', 'tickets.ticket_ref as ticket_ref','trips.price as price',
                    'bookings.firstname as firstname','buses.reg_number as reg_number','source.name as source','destination.name as destination',
                    'days.date as date','trips.depart_time as depart_time','trips.arrival_time as arrival_time','tickets.created_at as created_at',
                    'tickets.status as status','tickets.created_at','tickets.updated_at')
                    ->join('bookings', 'bookings.id', '=', 'tickets.booking_id')
                    ->join('schedules', 'schedules.id', '=', 'bookings.schedule_id')
                    ->join('days', 'days.id', '=', 'schedules.day_id')
                    ->join('trips', 'trips.id', '=', 'bookings.trip_id')
                    ->join('locations as source', 'source.id', '=', 'trips.source')
                    ->join('locations as destination', 'destination.id', '=', 'trips.destination')
                    ->join('buses', 'buses.id', '=', 'trips.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id');
            });

        $table = $this->setTableColumns($table);

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setTableColumns($table)
    {
        $table->addColumn('created_at')->setTitle('Date purchased')->isSearchable()->isSortable()->sortByDefault('desc')->setColumnDateFormat('Y-m-d H:i:s');

        $table->addColumn('ticket_ref')->isSearchable()->setTitle('Ticket Ref#')->useForDestroyConfirmation();
        $table->addColumn('price')->setTitle('Price')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('firstname')->setTitle('First name')->isSearchable()->isSortable()->setCustomTable('bookings')
            ->isCustomHtmlElement(function($entity, $column){
            return $entity['firstname'].' '.$entity['lastname'];
        });
        $table->addColumn('reg_number')->setTitle('Bus')->isSearchable()->isSortable()->setCustomTable('buses');
        $table->addColumn('source')->setTitle('From')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('destination')->setTitle('To')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('date')->setTitle('Travelling date')->isSearchable()->isSortable()->setCustomTable('days')
            ->isCustomHtmlElement(function($entity, $column){
            return $entity['date'].'<br>'.'('.$entity['depart_time'].' - '.$entity['arrival_time'].')';
        });
        //$table->addColumn('updated_at')->setTitle('Updated at')->isSortable()->isSearchable()->sortByDefault('desc');

        $table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function($entity, $column){
            return  $this->getTicketLabelByStatus($entity['status']);
        });
        return $table;
    }

    private function getTicketLabelByStatus($status){

        if ($status == Ticket::STATUS_CONFIRMED){
            return '<div class="label label-success">'.'Confirmed'.'</div>';
        }

        if ($status == Ticket::STATUS_VALID){
            return '<div class="label label-danger">'.'Payment pending'.'</div>';
        }

        if ($status == Ticket::STATUS_EXPIRED){
            return '<div class="label label-danger">'.'Expired'.'</div>';
        }

        return '<div class="label label-default">'.'Unknown'.'</div>';
    }
}