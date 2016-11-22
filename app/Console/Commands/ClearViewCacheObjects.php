<?php

namespace App\Console\Commands;

use Cache;
use Illuminate\Console\Command;

class ClearViewCacheObjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear_view_objects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cached view objects.';

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
        Cache::tags('views')->flush();

        $this->info('Clear objects cached for views.');
    }
}
