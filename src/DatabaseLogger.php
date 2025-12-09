<?php
namespace App;

use App\Interfaces\LoggerInterface;
use PDO; // Припустимо, у вас є PDO для БД

class DatabaseLogger implements LoggerInterface {
    public function __construct(private PDO $pdo) {}

    public function log(string $message): void {
        $stmt = $this->pdo->prepare("INSERT INTO logs (message, created_at) VALUES (?, NOW())");
        $stmt->execute([$message]);
        echo $message . "\n"; // Опціонально, для консолі
    }
}