<?php

namespace App\System\Binance;

use Binance\API;

class Client
{
    private API $api;

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    public function getHistoryHourly(\DateTime $dateFrom, \DateTime $dateTo, string $pair): array
    {
        return $this->api->candlesticks(
            $pair,
            '1h',
            null,
            (string)($dateFrom->getTimestamp() * 1000),
            (string)($dateTo->getTimestamp() * 1000)
        );
    }
}
