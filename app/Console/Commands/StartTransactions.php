<?php

namespace App\Console\Commands;

use App\Services\TransactionService;
use App\User;
use App\UserTransfer;
use Illuminate\Console\Command;

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
        $userTransaction = new UserTransfer();
        $user = new User();
        $transactionService = new TransactionService();
        $transactionService->beginTransactions($userTransaction, $user);
    }
}
