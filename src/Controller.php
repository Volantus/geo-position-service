<?php
namespace Volantus\GeoPositionService\Src;

use Symfony\Component\Console\Output\OutputInterface;
use Volantus\GeoPositionService\Src\GeoPosition\GeoPositionBufferingService;

/**
 * Class Controller
 * @package Volantus\GeoPositionService\Src
 */
class Controller extends \Volantus\FlightBase\Src\Server\Controller
{
    /**
     * Controller constructor.
     * @param OutputInterface $output
     * @param GeoPositionBufferingService $geoPositionBufferingService
     */
    public function __construct(OutputInterface $output, GeoPositionBufferingService $geoPositionBufferingService)
    {
        parent::__construct($output, $geoPositionBufferingService);
    }
}