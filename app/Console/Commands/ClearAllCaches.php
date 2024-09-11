<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAllCaches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all caches and optimize the application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('storage:clear');
        $this->call('view:clear');
        $this->call('cache:clear');
        $this->call('route:clear');
        $this->call('config:clear');
        $this->call('optimize:clear');

        $this->info('All caches cleared and application optimized.');

        return 0;
    }
}