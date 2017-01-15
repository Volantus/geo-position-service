<?php
namespace Volante\SkyBukkit\GeoPositionService\Src;

use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\GeoPositionService\Src\GeoPosition\GeoPositionBufferingService;

/**
 * Class Controller
 * @package Volante\SkyBukkit\GeoPositionService\Src
 */
class Controller extends \Volante\SkyBukkit\Common\Src\Server\Controller
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