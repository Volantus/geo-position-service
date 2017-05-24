<?php
namespace Volante\SkyBukkit\GeoPositionService\Src\GeoPosition;

use Nykopol\GpsdClient\Client;
use Volantus\FlightBase\Src\General\GeoPosition\GeoPosition;

/**
 * Class GeoPositionRepository
 *
 * @package Volante\SkyBukkit\GeoPositionService\Src\GeoPosition
 */
class GeoPositionRepository
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var GeoPosition
     */
    private $current;

    /**
     * GeoPositionRepository constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
        try {
            $this->client->connect();
            $this->client->watch();
        } catch (\Exception $exception) {
            throw new \RuntimeException('Unable to connect to GPSD daemon => ' . $exception->getMessage());
        }
    }

    /**
     * @return GeoPosition
     */
    public function getCurrentPosition() : GeoPosition
    {
        if ($this->current == null) {
            throw new \RuntimeException('No data available yet');
        }

        return $this->current;
    }

    public function refresh()
    {
        $data = $this->client->getNext('TPV', false);
        $data = json_decode($data, true);

        if (is_array($data) && isset($data['lat']) && isset($data['lon']) && isset($data['alt'])) {
            $this->current = new GeoPosition($data['lat'], $data['lon'], $data['alt']);
        }
    }
}