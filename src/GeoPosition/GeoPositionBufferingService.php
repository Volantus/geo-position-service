<?php
namespace Volante\SkyBukkit\GeoPositionService\Src\GeoPosition;

use Volante\SkyBukkit\Common\Src\Server\Messaging\Message;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageServerService;

/**
 * Class GeoPositionBufferingService
 * @package Volante\SkyBukkit\GeoPositionService\Src\GeoPosition
 */
class GeoPositionBufferingService extends MessageServerService
{
    /**
     * @param Message $message
     */
    protected function handleMessage(Message $message)
    {
        parent::handleMessage($message);

    }
}