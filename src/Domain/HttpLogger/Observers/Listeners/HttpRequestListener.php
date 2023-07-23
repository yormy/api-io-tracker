<?php

namespace Yormy\ApiIoTracker\Domain\HttpLogger\Observers\Listeners;

use Yormy\ApiIoTracker\Domain\HttpLogger\Services\UrlOnlyExcept;
use Yormy\StringGuard\Services\UrlGuard;

class HttpRequestListener
{
    public function handle(object $event): void
    {
        $url = $event->request->url();
        $method = $event->request->method();
        $config = config('api-io-tracker.url_guards');
        $include = UrlGuard::isIncluded($url, $method, $config);
        $data = UrlGuard::getData($url, $method, $config);

        if (!$include) {
            return;
        }
    }
}
