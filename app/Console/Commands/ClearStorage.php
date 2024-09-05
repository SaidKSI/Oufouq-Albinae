<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all folders and files inside the public/storage directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $storagePath = public_path('storage');

        if (File::exists($storagePath)) {
            $files = File::allFiles($storagePath);
            $directories = File::directories($storagePath);

            // Delete all files
            foreach ($files as $file) {
                File::delete($file);
            }

            // Delete all directories
            foreach ($directories as $directory) {
                File::deleteDirectory($directory);
            }

            $this->info('All folders and files inside public/storage have been deleted.');
        } else {
            $this->info('The public/storage directory does not exist.');
        }

        return 0;
    }
}