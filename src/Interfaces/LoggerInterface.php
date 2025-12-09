<?php
namespace App\Interfaces;

interface LoggerInterface {
    public function log(string $message): void;
}