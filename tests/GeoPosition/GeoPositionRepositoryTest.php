<?php
namespace Volante\SkyBukkit\GeoPositionService\Tests\GeoPosition;

use Nykopol\GpsdClient\Client;
use Volante\SkyBukkit\GeoPositionService\Src\GeoPosition\GeoPosition;
use Volante\SkyBukkit\GeoPositionService\Src\GeoPosition\GeoPositionRepository;

/**
 * Class GeoPositionRepositoryTest
 *
 * @package Volante\SkyBukkit\GeoPositionService\Tests\GeoPosition
 */
class GeoPositionRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * @var GeoPositionRepository
     */
    private $repository;

    protected function setUp()
    {
        $this->client = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $this->repository = new GeoPositionRepository($this->client);
    }

    public function test_refresh_correct()
    {
        $this->client->expects(self::once())
            ->method('getNext')->with('TPV', false)
            ->willReturn('{"class":"TPV","tag":"GBS","device":"/dev/ttyS0","mode":3,"time":"2017-01-15T19:57:07.000Z","ept":0.005,"lat":48.087344000,"lon":11.497316833,"alt":568.800,"epx":11.500,"epy":13.000,"epv":21.100,"track":0.0000,"speed":0.406,"climb":0.000,"eps":24.20}');

        $this->repository->refresh();
        $result = $this->repository->getCurrentPosition();
        self::assertInstanceOf(GeoPosition::class, $result);
        self::assertEquals(48.087344000, $result->getLatitude());
        self::assertEquals(11.497316833, $result->getLongitude());
        self::assertEquals(568.800, $result->getAltitude());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No data available yet
     */
    public function test_refresh_latMissing()
    {
        $this->client->expects(self::once())
            ->method('getNext')->with('TPV', false)
            ->willReturn('{"class":"TPV","tag":"GBS","device":"/dev/ttyS0","mode":3,"time":"2017-01-15T19:57:07.000Z","ept":0.005,"lon":11.497316833,"alt":568.800,"epx":11.500,"epy":13.000,"epv":21.100,"track":0.0000,"speed":0.406,"climb":0.000,"eps":24.20}');

        $this->repository->refresh();
        $this->repository->getCurrentPosition();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No data available yet
     */
    public function test_refresh_lonMissing()
    {
        $this->client->expects(self::once())
            ->method('getNext')->with('TPV', false)
            ->willReturn('{"class":"TPV","tag":"GBS","device":"/dev/ttyS0","mode":3,"time":"2017-01-15T19:57:07.000Z","ept":0.005,"lat":48.087344000,"alt":568.800,"epx":11.500,"epy":13.000,"epv":21.100,"track":0.0000,"speed":0.406,"climb":0.000,"eps":24.20}');

        $this->repository->refresh();
        $this->repository->getCurrentPosition();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No data available yet
     */
    public function test_refresh_altMissing()
    {
        $this->client->expects(self::once())
            ->method('getNext')->with('TPV', false)
            ->willReturn('{"class":"TPV","tag":"GBS","device":"/dev/ttyS0","mode":3,"time":"2017-01-15T19:57:07.000Z","ept":0.005,"lat":48.087344000,"lon":11.497316833,"epx":11.500,"epy":13.000,"epv":21.100,"track":0.0000,"speed":0.406,"climb":0.000,"eps":24.20}');

        $this->repository->refresh();
        $this->repository->getCurrentPosition();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No data available yet
     */
    public function test_refresh_noJson()
    {
        $this->client->expects(self::once())
            ->method('getNext')->with('TPV', false)
            ->willReturn('abc');

        $this->repository->refresh();
        $this->repository->getCurrentPosition();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No data available yet
     */
    public function test_getCurrentPosition_noDataAvailable()
    {
        $this->repository->getCurrentPosition();
    }
}