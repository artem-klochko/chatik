<?php
namespace App;

use App\Interfaces\LoggerInterface;

class SimpleLogger implements LoggerInterface {
    public function log(string $message): void {
        echo $message . "\n";
    }
}