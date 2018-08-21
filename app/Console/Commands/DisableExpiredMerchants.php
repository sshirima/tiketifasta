<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use App\Services\Merchants\AuthorizeMerchantAccount;
use Illuminate\Console\Command;

class DisableExpiredMerchants extends Command
{
    use AuthorizeMerchantAccount;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchants:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable all expired merchants';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $merchants = Merchant::contractExpired()->get();

        foreach ($merchants as $merchant){
            $this->disableMerchantAccount($merchant);
        }
    }
}
