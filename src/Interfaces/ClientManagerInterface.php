<?php
namespace App\Interfaces;

interface ClientManagerInterface {
    public function attach(\Ratchet\ConnectionInterface $conn): void;
    public function detach(\Ratchet\ConnectionInterface $conn): void;
    public function broadcast(string $message, \Ratchet\ConnectionInterface $exclude = null): void;
    public function count(): int;
}