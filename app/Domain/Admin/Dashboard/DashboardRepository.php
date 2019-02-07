<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 2/7/2019
 * Time: 2:47 PM
 */

namespace App\Domain\Admin\Dashboard;

use Illuminate\Support\Facades\DB;

trait DashboardRepository
{

    protected function getServers(){
        return config('dashboard_admin.servers');
    }

    protected function getMerchantCountByStatus($status){
        return DB::table('merchants')->where(['status'=>$status])->count();
    }

    protected function getBusesCountAll(){
        return DB::table('buses')->count();
    }

    protected function getTicketsCountByStatus($status){
        return DB::table('tickets')->where(['status'=>$status])->count();
    }
}