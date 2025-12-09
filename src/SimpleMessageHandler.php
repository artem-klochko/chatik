<?php
namespace App;

use App\Interfaces\MessageHandlerInterface;
use App\Interfaces\ClientManagerInterface;
use App\Interfaces\LoggerInterface;
use Ratchet\ConnectionInterface;

class SimpleMessageHandler implements MessageHandlerInterface {
    public function __construct(
        private ClientManagerInterface $clientManager,
        private LoggerInterface $logger
    ) {}

    public function handle(ConnectionInterface $from, string $message): void {
        $numRecv = $this->clientManager->count() - 1;
        $logMsg = sprintf('З\'єднання %d надсилає "%s" до %d інших', $from->resourceId, $message, $numRecv);
        $this->logger->log($logMsg);

        $this->clientManager->broadcast($message, $from);
    }
    
    public function getLogger() {
        return $this->logger;
    }
}