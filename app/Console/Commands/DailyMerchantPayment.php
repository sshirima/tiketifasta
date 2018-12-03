<?php

namespace App\Console\Commands;

use App\Services\Payments\MerchantPayments\MerchantPaymentProcessor;
use Illuminate\Console\Command;

class DailyMerchantPayment extends Command
{
    use MerchantPaymentProcessor;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchants:daily-payments {--date= : Date when the payment has been done}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily payments to merchants';

    /**
     * Create a new command instance.
     *
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
        $d = $this->option('date');//date('Y-m-d');

        $date = isset($d)?$d:date('Y-m-d');

        $response = $this->processPayments($date);

        return $response;
    }
}
