<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 2/1/2019
 * Time: 3:12 PM
 */

namespace App\Domain\Admin\MonitorSystem\Services;


use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

trait RunCommandsHostServer
{
    /**
     * @param $host
     * @param $count
     * @return array
     */
    protected function pingIp($host, $count){
        $echo_response = 0;
        $echo_timeout = 0;
        try{
            exec("ping $host -c $count", $output, $status);

            foreach ($output as $key=>$line){
                $line = ltrim($line);

                if(preg_match("/^64 bytes from/i", $line)){
                    $echo_response++;
                    continue;
                }

                if(preg_match("/^Request timed out/i", $line)){
                    $echo_timeout++;
                    continue;
                }
            }
            $response = ['status'=>$this->getPingStatusFromCommandOutput($echo_response,$echo_timeout),
                'host'=>$host, 'echo_response'=>$echo_response,'echo_timeout'=>$echo_timeout/*,'output'=>$output*/];
        }catch(\Exception $ex){
            $response = ['status'=>false,'error'=>$ex->getMessage()];
        }
        //print json_encode($output);
        return $response;
    }

    /**
     * @param $host
     * @param $port
     * @return array
     */
    protected function telnetIp($host, $port){
        $hasTelnet = false;
        try{

            $process = new Process("telnet $host $port");
            $process->setTimeout(60);
            $process->setIdleTimeout(5);
            $process->run();

            $iterator = $process->getIterator($process::ITER_SKIP_ERR | $process::ITER_KEEP_OUTPUT);

            /*if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }*/

            $output = $process->getOutput();

            if(preg_match("/Connected to $host/i", $output)){
                $hasTelnet = true;
            }

            $response = ['status'=>$hasTelnet, 'host'=>$host,'message'=>$output];
        }catch (\Exception $ex){
            $response = ['status'=>$hasTelnet, 'host'=>$host, 'error'=>$ex->getMessage()];
        }
        return $response;
    }


    /**
     * @param $echo_response
     * @param $echo_timeout
     * @return bool
     */
    private function getPingStatusFromCommandOutput($echo_response, $echo_timeout){
        $status = false;
        if($echo_response == 0 && $echo_timeout ==0){
            return $status;
        }
        $response_status = ($echo_response)/($echo_timeout+$echo_response)*100;
        if($response_status > 0){
            $status = true;
        }
        return $status;
    }
}