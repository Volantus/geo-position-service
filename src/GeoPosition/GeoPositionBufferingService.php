<?php
namespace Volante\SkyBukkit\GeoPositionService\Src\GeoPosition;

use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageServerService;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageService;
use Volante\SkyBukkit\Common\Src\Server\Network\ClientFactory;

/**
 * Class GeoPositionBufferingService
 * @package Volante\SkyBukkit\GeoPositionService\Src\GeoPosition
 */
class GeoPositionBufferingService extends MessageServerService
{
    /**
     * @var GeoPositionRepository
     */
    private $geoPositionRepository;

    /**
     * GeoPositionBufferingService constructor.
     *
     * @param OutputInterface       $output
     * @param LoopInterface         $loop
     * @param MessageService|null   $messageService
     * @param ClientFactory|null    $clientFactory
     * @param GeoPositionRepository $geoPositionRepository
     */
    public function __construct(OutputInterface $output, LoopInterface $loop, MessageService $messageService = null, ClientFactory $clientFactory = null, GeoPositionRepository $geoPositionRepository = null)
    {
        parent::__construct($output, $messageService, $clientFactory);

        $this->geoPositionRepository = $geoPositionRepository ?: new GeoPositionRepository();
        $loop->addPeriodicTimer(0.5, function () {
            $this->sendPosition();
        });
    }

    public function sendPosition()
    {
        $this->sandbox(function () {
            $this->writeInfoLine('GeoPositionBufferingService', 'Refreshing current position...');
            $this->geoPositionRepository->refresh();

            $this->writeInfoLine('GeoPositionBufferingService', 'Broadcasting new position...');
            $geoPosition = $this->geoPositionRepository->getCurrentPosition();
            $geoPosition = $geoPosition->toRawMessage();
            $this->broadcastMessage($geoPosition);
            $this->writeInfoLine('GeoPositionBufferingService', 'Broadcasting finished!');
        });
    }
}