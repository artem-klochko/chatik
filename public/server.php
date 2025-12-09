<?php

const ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

require ROOT . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once ROOT . 'src' . DIRECTORY_SEPARATOR . 'Interfaces' . DIRECTORY_SEPARATOR . 'ClientManagerInterface.php';
require_once ROOT . 'src' . DIRECTORY_SEPARATOR . 'Interfaces' . DIRECTORY_SEPARATOR . 'MessageHandlerInterface.php';
require_once ROOT . 'src' . DIRECTORY_SEPARATOR . 'Interfaces' . DIRECTORY_SEPARATOR . 'LoggerInterface.php';
require_once ROOT . 'src' . DIRECTORY_SEPARATOR . 'ClientManager.php';
require_once ROOT . 'src' . DIRECTORY_SEPARATOR . 'SimpleMessageHandler.php';
require_once ROOT . 'src' . DIRECTORY_SEPARATOR . 'SimpleLogger.php';  // Або DatabaseLogger для БД

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\ClientManager;
use App\SimpleMessageHandler;
use App\SimpleLogger;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

class ChatServer implements MessageComponentInterface {
    public function __construct(
        private ClientManager $clientManager,
        private SimpleMessageHandler $messageHandler,
        private SimpleLogger $logger
    ) {
        echo "Сервер запущено!\n";
    }

    public function onOpen(ConnectionInterface $conn): void {
        $this->clientManager->attach($conn);
        $this->logger->log("Нове з'єднання: ({$conn->resourceId})"); // Доступ через handler, якщо потрібно
    }

    public function onMessage(ConnectionInterface $from, $msg): void {
        $this->messageHandler->handle($from, $msg);
    }

    public function onClose(ConnectionInterface $conn): void {
        $this->clientManager->detach($conn);
        $this->logger->log("З'єднання ({$conn->resourceId}) закрито");
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void {
        $this->logger->log("Помилка: {$e->getMessage()}");
        $conn->close();
    }
}

var_dump(getenv('ENVIRONMENT')); die;

// Конструктор з інжекцією залежностей (DIP)
$clientManager = new ClientManager();
$logger = new SimpleLogger(); // Або new DatabaseLogger($pdo);
$messageHandler = new SimpleMessageHandler($clientManager, $logger);

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer($clientManager, $messageHandler, $logger)
        )
    ),
    8040
);

$server->run();