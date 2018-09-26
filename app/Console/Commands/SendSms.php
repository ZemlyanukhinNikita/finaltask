<?php

namespace App\Console\Commands;

use App\Services\SmsService;
use App\UserTransfer;
use Illuminate\Console\Command;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sens sms about transaction every day';

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
        $smsService = new SmsService();
        $userTransfer = new UserTransfer();
        $countSuccessTransfers = $userTransfer->getCountSuccessTransfers();
        $sumAmountSuccessTransfers = $userTransfer->getAmountSumSuccessTransfers();
        $textSms = "За сегодня: успешных транзакций: {$countSuccessTransfers} 
                    Сумма успешных: {$sumAmountSuccessTransfers}";

        $smsService->sendSms('79609177819', $textSms);
    }
}
