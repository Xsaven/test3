<?php

namespace App\Console\Commands;

use App\Interfaces\AdRequestServiceInterface;
use Illuminate\Console\Command;

class AdInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad:info {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get information about the ad';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = app(AdRequestServiceInterface::class);

        $service->setUrl(
            $this->argument('url')
        );

        $this->info('Price: ' . $service->getAdPrice());
    }
}
