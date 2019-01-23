<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 1/16/2019
 * Time: 6:06 PM
 */

namespace App\Http\Controllers\Admins\CollectionReport;


use App\Http\Controllers\Admins\BaseController;
use App\Models\BookingPayment;
use App\Models\Ticket;
use App\Reports\Admin\Collections\C2BCollectionsByBuses;
use App\Reports\Admin\Collections\C2BCollectionsByDate;
use App\Reports\Admin\Collections\C2BCollectionsByMerchant;
use App\Reports\Admin\Tickets\TicketsCountByDate;

class C2BCollectionsReportController extends BaseController
{
    protected $condition;

    public function byDate()
    {

        $report = new C2BCollectionsByDate(array('transaction_status'=>BookingPayment::TRANS_STATUS_SETTLED));
        $report->run();
        return view("admins.pages.reports.c2b_collections_by_date")->with(["report"=>$report]);
    }

    public function byMerchants()
    {
        $report = new C2BCollectionsByMerchant(array('transaction_status'=>BookingPayment::TRANS_STATUS_SETTLED));
        $report->run();
        return view("admins.pages.reports.c2b_collections_by_merchant")->with(["report"=>$report]);
    }

    public function byBuses()
    {
        $report = new C2BCollectionsByBuses(array('transaction_status'=>BookingPayment::TRANS_STATUS_SETTLED));
        $report->run();
        return view("admins.pages.reports.c2b_collections_by_buses")->with(["report"=>$report]);
    }

    public function ticketsCount()
    {
        $report = new TicketsCountByDate(array('status'=>Ticket::STATUS_CONFIRMED));
        $report->run();
        return view("admins.pages.reports.tickets_counts_by_date")->with(["report"=>$report]);
    }
}