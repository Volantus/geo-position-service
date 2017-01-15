<?php
namespace Volante\SkyBukkit\GeoPositionService\Tests\GeoPosition;
use Volante\SkyBukkit\Common\Src\General\Network\RawMessage;
use Volante\SkyBukkit\GeoPositionService\Src\GeoPosition\GeoPosition;

/**
 * Class GeoPositionTest
 *
 * @package Volante\SkyBukkit\GeoPositionService\Tests\GeoPosition
 */
class GeoPositionTest extends \PHPUnit_Framework_TestCase
{
    public function test_toRawMessage_correct()
    {
        $expected = [
            'latitude'  => 30.1001711,
            'longitude' => 50.7974963,
            'altitude'  => 512.15
        ];

        $geoPosition = new GeoPosition($expected['latitude'], $expected['longitude'], $expected['altitude']);
        $rawMessage = $geoPosition->toRawMessage();

        self::assertInstanceOf(RawMessage::class, $rawMessage);
        self::assertEquals('Geo position', $rawMessage->getTitle());
        self::assertEquals('geoPosition', $rawMessage->getType());
        self::assertEquals($expected, $rawMessage->getData());
    }
}