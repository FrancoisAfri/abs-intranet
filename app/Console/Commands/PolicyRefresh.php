<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PolicyRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:policyRefresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will command will remind employees to revise policies';

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
        app('App\Service\PolicyRefresh')->execute();
        \Log::info('Cron - PolicyRefresh, artisan command schedule:montlyAssetDepreciation ran successfully @ ' . \Carbon\Carbon::now());
    }
}
