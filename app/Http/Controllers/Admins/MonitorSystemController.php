<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 1/16/2019
 * Time: 6:06 PM
 */

namespace App\Http\Controllers\Admins;

use App\Domain\Admin\MonitorSystem\MonitorSystemAggregate;
use Illuminate\Http\Request;

class MonitorSystemController extends BaseController
{
    protected $monitorSystemAggregate;

    public function __construct(MonitorSystemAggregate $monitorSystemAggregate)
    {
        parent::__construct();
        $this->monitorSystemAggregate = $monitorSystemAggregate;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pingServerIp(Request $request){

        if($request->has('ip')){
            return response()->json($this->monitorSystemAggregate->pingServerIp($request->get('ip')));
        } else{
            return response()->json(['status'=>false,'error'=>'Server ip not specified']);
        }
    }
}