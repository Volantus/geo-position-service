<?php
namespace Volantus\GeoPositionService\Src\Commands;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Volantus\GeoPositionService\Src\Controller;
use Volantus\GeoPositionService\Src\GeoPosition\GeoPositionBufferingService;
use React\EventLoop\Factory as LoopFactory;
use React\Socket\Server as Reactor;

/**
 * Class ServerCommand
 * @package Volantus\GeoPositionService\Src\Commands
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
        $loop   = LoopFactory::create();
        $socket = new Reactor($loop);
        $socket->listen($input->getOption('port'), '127.0.0.1');

        $service = new GeoPositionBufferingService($output, $loop);
        $controller = new Controller($output, $service);

        $server = new IoServer(new HttpServer(new WsServer($controller)), $socket, $loop);
        $server->run();
    }
}