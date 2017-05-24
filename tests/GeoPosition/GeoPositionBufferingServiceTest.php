<?php
namespace Volante\SkyBukkit\GeoPositionService\Tests\GeoPosition;

use Ratchet\ConnectionInterface;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Tests\Fixtures\DummyOutput;
use Volantus\FlightBase\Src\General\GeoPosition\GeoPosition;
use Volantus\FlightBase\Src\General\Role\ClientRole;
use Volantus\FlightBase\Src\Server\Messaging\MessageService;
use Volantus\FlightBase\Src\Server\Network\Client;
use Volantus\FlightBase\Src\Server\Network\ClientFactory;
use Volantus\FlightBase\Tests\Server\General\DummyConnection;
use Volante\SkyBukkit\GeoPositionService\Src\GeoPosition\GeoPositionBufferingService;
use Volante\SkyBukkit\GeoPositionService\Src\GeoPosition\GeoPositionRepository;

/**
 * Class GeoPositionBufferingServiceTest
 *
 * @package Volante\SkyBukkit\GeoPositionService\Tests\GeoPosition
 */
class GeoPositionBufferingServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GeoPositionBufferingService
     */
    private $service;

    /**
     * @var ClientFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $clientFactory;

    /**
     * @var GeoPositionRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $geoPositionRepository;

    protected function setUp()
    {
        /** @var LoopInterface $loopInterface */
        $loopInterface = $this->getMockBuilder(LoopInterface::class)->getMock();
        $messagingService = $this->getMockBuilder(MessageService::class)->disableOriginalConstructor()->getMock();
        $this->clientFactory = $this->getMockBuilder(ClientFactory::class)->disableOriginalConstructor()->getMock();
        $this->geoPositionRepository = $this->getMockBuilder(GeoPositionRepository::class)->disableOriginalConstructor()->getMock();

        $this->service = new GeoPositionBufferingService(new DummyOutput(), $loopInterface, $messagingService, $this->clientFactory, $this->geoPositionRepository);
    }

    public function test_sendPosition_positionRefreshedAndSend()
    {
        $connection = $this->getMockBuilder(ConnectionInterface::class)->disableOriginalConstructor()->getMock();
        $connection->expects(self::once())->method('send')->with('{"type":"geoPosition","title":"Geo position","data":{"latitude":1,"longitude":2,"altitude":3}}');
        /** @var ConnectionInterface $connection */
        $this->clientFactory->method('get')->willReturn(new Client(1, $connection, ClientRole::STATUS_BROKER));

        $this->geoPositionRepository->expects(self::once())->method('refresh');
        $this->geoPositionRepository->expects(self::once())->method('getCurrentPosition')->willReturn(new GeoPosition(1, 2, 3));

        $this->service->newClient(new DummyConnection());
        $this->service->sendPosition();
    }
}