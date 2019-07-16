<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FleetManagementDocsUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fleet:documentsUpload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will get files from a directory from another';

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
        app('App\Http\Controllers\FleetManagementUploadDocumentsCron')->execute();
        \Log::info('Cron - FleetManagementDocsUpload, artisan command fleet:documentsUpload ran successfully @ ' . \Carbon\Carbon::now());
    }
}