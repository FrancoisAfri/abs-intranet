<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AssetDepreciation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:montlyAssetDepreciation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will command will perform asset depreciation for all assets';

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
        app('App\Service\AssetDepreciation')->depreciation();
        \Log::info('Cron - AssetDepreciation, artisan command schedule:montlyAssetDepreciation ran successfully @ ' . \Carbon\Carbon::now());
    }
}
