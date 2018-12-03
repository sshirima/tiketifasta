<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/3/2018
 * Time: 11:09 PM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Models\ScheduledTask;
use App\Services\SMS\Smpp;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use LaravelSmpp\SmppService;

class ScheduledTasksController extends Controller
{

   public function after(Request $request, $uuid){

       $task = ScheduledTask::where(['task_uid'=>$uuid])->first();

       if(isset($task)){
           ScheduledTask::setTaskCompleted($task);
            return 'success';
       } else{
           return 'fail';
       }
   }
}