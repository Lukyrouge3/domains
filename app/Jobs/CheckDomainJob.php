<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\DNSEntry;

class CheckDomainJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $domain
    ) {
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->getDNS();
    }

    private function getDNS(): void
    {
        $records = dns_get_record($this->domain, DNS_A);

        if (empty($records)) {
            logger()->warning("No DNS A record found for {$this->domain}");
            return;
        }

        foreach ($records as $record) {
            $host = $record['host'];
            $ip = $record['ip'];
            $class = $record['class'];
            $type = $record['type'];
            $expiresAt = now()->addSeconds($record['ttl'] ?? 0);

            if ($host && $ip && $type) {
                DNSEntry::create(
                    [
                        'host' => $host,
                        'type' => $type,
                        'ip' => $ip,
                        'class' => $class,
                        'expires_at' => $expiresAt,
                    ]
                );
            }
        }
    }
}
