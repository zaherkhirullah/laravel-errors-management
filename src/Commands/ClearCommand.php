<?php

namespace Hayrullah\ErrorManagement\Commands;

use Illuminate\Console\Command;

class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'error_records:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Command for Clear All of cache, view, route config';

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
        $this->call("cache:clear");
        $this->call('config:clear');
        $this->call('event:clear');
        $this->call('route:clear');
//        $this->call('geoip:clear');
//        $this->call('route:trans:clear');
        $this->call('view:clear');
    }
}
