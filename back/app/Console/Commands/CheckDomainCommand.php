<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Jobs\CheckDomainJob;

#[Signature('domains:check {domain}')]
#[Description('Command description')]
class CheckDomainCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domainName = $this->argument('domain');
        $this->info("Checking {$domainName}...");

        CheckDomainJob::dispatch($domainName);

        $this->info("Done.");
    }
}
