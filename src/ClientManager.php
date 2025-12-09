<?php
namespace App;

use App\Interfaces\ClientManagerInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

class ClientManager implements ClientManagerInterface {
    private SplObjectStorage $clients;

    public function __construct() {
        $this->clients = new SplObjectStorage;
    }

    public function attach(ConnectionInterface $conn): void {
        $this->clients->attach($conn);
    }

    public function detach(ConnectionInterface $conn): void {
        $this->clients->detach($conn);
    }

    public function broadcast(string $message, ConnectionInterface $exclude = null): void {
        foreach ($this->clients as $client) {
            if ($exclude !== $client) {
                $client->send($message);
            }
        }
    }

    public function count(): int {
        return count($this->clients);
    }
}