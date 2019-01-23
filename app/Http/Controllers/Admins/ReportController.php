<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 1/16/2019
 * Time: 6:06 PM
 */

namespace App\Http\Controllers\Admins;


use App\Http\Controllers\Controller;
use App\Reports\MyReport;

class ReportController extends Controller
{
    public function index()
    {
        $report = new MyReport();
        $report->run();
        return view("admins.pages.reports.report")->with(["report"=>$report]);
    }

    public function exportPDF(){
        $report = new MyReport();
        $report->run()->export('OrderPdf')
            ->pdf(array(
                "format"=>"A4",
                "orientation"=>"portrait",
                //"zoom"=>2,
                "margin"=>"1in"
            ))
            ->toBrowser("orders.pdf");
    }
}