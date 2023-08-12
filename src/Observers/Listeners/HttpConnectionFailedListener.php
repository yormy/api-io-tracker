<?php

namespace Yormy\ApiIoTracker\Observers\Listeners;

use Yormy\ApiIoTracker\DataObjects\LogOutgoingData;
use Yormy\ApiIoTracker\Models\LogHttpOutgoing;
use Yormy\StringGuard\Services\UrlGuard;

class HttpConnectionFailedListener
{
    public function handle(object $event): void
    {
        $url = $event->request->url();
        $method = $event->request->method();
        $config = config('api-io-tracker.outgoing_url_guards');
        $include = UrlGuard::isIncluded($url, $method, $config);
        $data = UrlGuard::getData($url, $method, $config);

        if (! $include) {
            return;
        }

        $logData = LogOutgoingData::make($event->request, null, $data);

        LogHttpOutgoing::create([
            'status' => 'FAILED',
            ...$logData,
        ]);
    }
}
