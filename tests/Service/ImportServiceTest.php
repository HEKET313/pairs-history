<?php

namespace App\Tests\Service;

use App\Repository\PairDataRepository;
use App\Repository\PairRepository;
use App\Service\ImportService;
use App\System\Binance\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ImportServiceTest extends TestCase
{
    /**
     * @dataProvider importNewDataDP
     * @param array $binanceApiReturn
     */
    public function testImportNewData(array $binanceApiReturn, callable $importChecker, callable $triggerAssertions)
    {
        /** @var MockObject|Client $client */
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['getHistoryHourly'])
            ->getMock();
        $client->method('getHistoryHourly')->willReturn($binanceApiReturn);

        /** @var MockObject|PairRepository $pairRepository */
        $pairRepository = $this->getMockBuilder(PairRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAllWithLastUpdate'])
            ->getMock();
        $pairRepository->method('getAllWithLastUpdate')->willReturn([[
            'id' => 12,
            'name' => 'BTCUSDT',
            'last_update' => '2020-05-10 12:00:00',
        ]]);

        /** @var MockObject|PairDataRepository $pairDataRepository */
        $pairDataRepository = $this->getMockBuilder(PairDataRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['importData'])
            ->getMock();
        $pairDataRepository->method('importData')->will(new ReturnCallback($importChecker));
        /** @var LoggerInterface $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $service = new ImportService(
            $client,
            $pairRepository,
            $pairDataRepository,
            $logger
        );
        $service->importNewData();
        $triggerAssertions();
    }

    public function importNewDataDP(): iterable
    {
        $now = time() * 1000;
        $assertions = [];
        $makeTriggerAssertions = function (&$assertions): callable {
            return function () use (&$assertions) {
                foreach ($assertions as $assert) {
                    $assert();
                }
            };
        };
        yield 'Success 2 items' => [
            [
                ['openTime' => $now - 3600 * 1000, 'close' => 8800.00],
                ['openTime' => $now, 'close' => 8700.00],
            ],
            function (int $id, array $data) use (&$assertions) {
                $dataSize = sizeof($data);
                $assertions[] = function () use ($dataSize) {
                    $this->assertEquals(2, $dataSize);
                };
            },
            $makeTriggerAssertions($assertions)
        ];

        $assertions2 = [];
        $i = 0;
        yield 'Success 101 items' => [
            array_fill(0, 101, ['openTime' => $now - 3600 * 1000, 'close' => 8800.00]),
            function (int $id, array $data) use (&$i, &$assertions2) {
                $expSize = $i === 0 ? 100 : 1;
                $dataSize = sizeof($data);
                $assertions2[] = function () use ($expSize, $dataSize) {
                    $this->assertEquals($expSize, $dataSize);
                };
                $i++;
            },
            $makeTriggerAssertions($assertions2)
        ];
    }
}
