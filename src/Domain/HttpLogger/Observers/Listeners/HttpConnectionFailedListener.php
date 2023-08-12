<?php

namespace Yormy\ApiIoTracker\Domain\HttpLogger\Observers\Listeners;

use Yormy\ApiIoTracker\Domain\HttpLogger\Models\LogHttpOutgoing;
use Yormy\ApiIoTracker\DTO\LogData;
use Yormy\StringGuard\Services\UrlGuard;

class HttpConnectionFailedListener
{
    public function handle(object $event): void
    {
        $url = $event->request->url();
        $method = $event->request->method();
        $config = config('api-io-tracker.url_guards');
        $include = UrlGuard::isIncluded($url, $method, $config);
        $data = UrlGuard::getData($url, $method, $config);

        if (! $include) {
            return;
        }

        $logData = LogData::make($event->request, null, $data);

        LogHttpOutgoing::create([
            'status' => 'FAILED',
            ...$logData,
        ]);
    }
}
