<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use App\Services\Merchants\AuthorizeMerchantAccount;
use App\Services\Merchants\MerchantAuthorization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DisableExpiredMerchants extends Command
{
    use MerchantAuthorization;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchants:contract-disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable all expired merchants';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Query all expired merchants
        //$merchants = Merchant::contractExpired()->get();

        if(config('app.debug_logs')) {
            \Log::info('Running scheduled command: '.$this->signature .' at '.date('Y-m-d H:i:s'));
        }

        $status = 0;

        $merchants = Merchant::where(['status'=>!$status])->whereDate(Merchant::COLUMN_CONTRACT_END, '<', date('Y-m-d'))->get();

        foreach ($merchants as $merchant){

            $this->authorizeMerchant($merchant, $status);
        }

        Log::info('{'. count($merchants).'} merchants accounts status changed to '.$status);

    }
}
