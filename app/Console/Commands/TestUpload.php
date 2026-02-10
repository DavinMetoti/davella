<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test file upload functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing file upload functionality...');

        // Test if directory exists
        $this->info('Checking if profile-photos directory exists...');
        if (!Storage::exists('public/profile-photos')) {
            $this->warn('Directory does not exist, creating...');
            Storage::makeDirectory('public/profile-photos');
        } else {
            $this->info('Directory exists');
        }

        // Test writing a file
        $this->info('Testing file write...');
        $testContent = 'Test file content ' . now();
        $fileName = 'test_' . time() . '.txt';

        if (Storage::put('public/profile-photos/' . $fileName, $testContent)) {
            $this->info('File written successfully: ' . $fileName);

            // Check if file exists
            if (Storage::exists('public/profile-photos/' . $fileName)) {
                $this->info('File exists in storage');
                $this->info('File URL: ' . Storage::url('public/profile-photos/' . $fileName));
            } else {
                $this->error('File does not exist after writing');
            }
        } else {
            $this->error('Failed to write file');
        }

        // List files in directory
        $this->info('Files in profile-photos directory:');
        $files = Storage::files('public/profile-photos');
        foreach ($files as $file) {
            $this->line(' - ' . $file);
        }
    }
}
