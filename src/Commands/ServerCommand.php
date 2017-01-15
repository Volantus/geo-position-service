<?php
namespace Volante\SkyBukkit\GeoPositionService\Src\Commands;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Volante\SkyBukkit\GeoPositionService\Src\Controller;
use Volante\SkyBukkit\GeoPositionService\Src\GeoPosition\GeoPositionBufferingService;

/**
 * Class ServerCommand
 * @package Volante\SkyBukkit\GeoPositionService\Src\Commands
 */
class ServerCommand extends Command
{
    protected function configure()
    {
        $this->setName('server');
        $this->setDescription('Runs the relay server');

        $this->addOption('port', 'p', InputArgument::OPTIONAL, 'Port of the webSocket', 8080);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = new GeoPositionBufferingService($output);
        $controller = new Controller($output, $service);

        $server = IoServer::factory(new HttpServer(new WsServer($controller)), $input->getOption('port'));
        $server->run();
    }
}