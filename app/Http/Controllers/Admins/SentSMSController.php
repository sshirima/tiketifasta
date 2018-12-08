<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/5/2018
 * Time: 10:30 PM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admin\SendSMSRequest;
use App\Models\SentSMS;
use App\Services\SMS\SendSMS;
use App\Services\SMS\Smpp;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class SentSMSController extends BaseController
{

    use SendSMS;

    private $smpp;
    /**
     * SentSMSController constructor.
     * @param Smpp $smpp
     */
    public function __construct(Smpp $smpp)
    {
        parent::__construct();
        $this->smpp=$smpp;
    }

    public function index(Request $request){
        $table = $this->createSentSMSTable();
        return view('admins.pages.sms.index_sent_sms')->with(['table' => $table]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sendSMS(){
        return view('admins.pages.sms.send_sms');
    }

    /**
     * @param SendSMSRequest $request
     * @return string
     */
    public function sendSMSSubmit(SendSMSRequest $request){
        $input = $request->all();

        $sendSMS = $this->sendOneSMS($input['operator'],$input['receiver'],$input['message']);

         if ($sendSMS['status']){
             return view('admins.pages.sms.send_sms')->with(['sentStatus'=>true,'phoneNumber'=>$input['receiver']]);
         }else{
             return view('admins.pages.sms.send_sms')->with(['sentStatus'=>false,'error'=>$sendSMS['error']]);
         }
    }

    /**
     * @return mixed
     */
    protected function createSentSMSTable()
    {
        $table = app(TableList::class)
            ->setModel(SentSMS::class)
            ->setRowsNumber(20)
            ->setRoutes([
                'index' => ['alias' => 'admin.sent_sms.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('id','receiver','sender','operator','message','created_at','updated_at','is_sent');
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
        $table->addColumn('receiver')->setTitle('Sent to')->isSortable()->isSearchable();

        $table->addColumn('message')->setTitle('Message')->isSortable()->isSearchable();

        $table->addColumn('sender')->setTitle('Sender ID')->isSortable()->isSearchable();

        $table->addColumn('operator')->setTitle('Operator')->isSearchable();

        $table->addColumn('is_sent')->setTitle('Sent status')->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['is_sent']?
                '<div class="label label-success">'.'Sent'.'</div>':'<div class="label label-danger">'.'Failed'.'</div>';
        });

        $table->addColumn('created_at')->setTitle('Created at')->isSortable()->isSearchable()->sortByDefault('desc');

        return $table;
    }
}