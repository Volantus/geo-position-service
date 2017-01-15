<?php
namespace Volante\SkyBukkit\GeoPositionService\Src\GeoPosition;

use Volante\SkyBukkit\Common\Src\Client\Message;

/**
 * Class GeoPosition
 * @package Volante\SkyBukkit\GeoPositionService\Src\GeoPosition
 */
class GeoPosition extends Message
{
    /**
     * @var string
     */
    protected $type = 'geoPosition';

    /**
     * @var string
     */
    protected $messageTitle = 'Geo position';

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $altitude;

    /**
     * GeoPosition constructor.
     * @param float $latitude
     * @param float $longitude
     * @param float $altitude
     */
    public function __construct(float $latitude, float $longitude, float $altitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->altitude = $altitude;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @return float
     */
    public function getAltitude(): float
    {
        return $this->altitude;
    }

    /**
     * @return array
     */
    protected function getRawData(): array
    {
        return [
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'altitude'  => $this->altitude
        ];
    }
}