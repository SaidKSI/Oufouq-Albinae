<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshDatabase extends Command
{
    protected $signature = 'db:refresh';

    protected $description = 'Refreshes the database by wiping, migrating, and seeding';

    public function handle()
    {
        if ($this->confirm('Are you sure you want to refresh the database? This action will wipe all data.')) {
            $this->call('db:wipe');
            $this->call('migrate:refresh');
            $this->call('db:seed');
            $this->info('Database refreshed successfully.');
        } else {
            $this->info('Database refresh operation cancelled.');
        }
    }
}