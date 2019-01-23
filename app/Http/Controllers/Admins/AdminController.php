<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 10:18 PM
 */

namespace App\Http\Controllers\Admins;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AdminController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function homepage(){
        $process = new Process('whoami');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $data= $process->getOutput();

        return view('admins.pages.home')->with(['data'=>$data]);
    }

}