<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoyzerService;

class TestGoyzerConnection extends Command
{
    protected $signature = 'goyzer:test';
    protected $description = 'Test connection to Goyzer API';

    protected $goyzer;

    public function __construct(GoyzerService $goyzer)
    {
        parent::__construct();
        $this->goyzer = $goyzer;
    }

    public function handle()
    {
        $this->info('Testing Goyzer API connection...');

        $result = $this->goyzer->testConnection();

        if ($result['success']) {
            $this->info('✅ Connection successful!');
            
            if (isset($result['data']['Count'])) {
                $this->info("Found {$result['data']['Count']} listings");
            }
        } else {
            $this->error('❌ Connection failed!');
            $this->error('Please check your GOYZER_ACCESS_CODE and GOYZER_GROUP_CODE in .env file');
        }

        return $result['success'] ? 0 : 1;
    }
}