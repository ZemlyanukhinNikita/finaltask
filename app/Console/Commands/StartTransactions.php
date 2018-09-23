<?php

namespace App\Console\Commands;

use App\UserTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StartTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start scheduled transactions';

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
        $time = Carbon::now();
        \Log::info('i was here in '. $time);
//        $transaction = new UserTransaction();
//        $transaction->beginTransactions();
    }
}
