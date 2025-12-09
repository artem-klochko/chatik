<?php
namespace App\Interfaces;

interface MessageHandlerInterface {
    public function handle(\Ratchet\ConnectionInterface $from, string $message): void;
}