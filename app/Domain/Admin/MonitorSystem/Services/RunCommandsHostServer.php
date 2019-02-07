<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 2/1/2019
 * Time: 3:12 PM
 */

namespace App\Domain\Admin\MonitorSystem\Services;


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
            $response = ['status'=>$this->getResponseStatus($echo_response,$echo_timeout),
                'host'=>$host, 'echo_response'=>$echo_response,'echo_timeout'=>$echo_timeout/*,'output'=>$output*/];
        }catch(\Exception $ex){
            $response = ['status'=>false,'error'=>$ex->getMessage()];
        }
        //print json_encode($output);
        return $response;
    }

    /**
     * @param $echo_response
     * @param $echo_timeout
     * @return bool
     */
    private function getResponseStatus($echo_response, $echo_timeout){
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