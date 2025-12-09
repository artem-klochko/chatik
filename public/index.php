<?php

const ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
require ROOT . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Чатік</title>
    <style>
        #chat { width: 400px; height: 300px; border: 1px solid #ccc; overflow-y: scroll; padding: 10px; }
        input { width: 300px; }
        button { width: 100px; }
    </style>
</head>
<body>
    <div id="chat"></div>
    <input type="text" id="message" placeholder="Введіть повідомлення">
    <button onclick="sendMessage()">Надіслати</button>

    <script>
        const server = "<?php echo $_ENV['SERVER'] ?>";
        const port = "<?php echo $_ENV['PORT'] ?>";
        const ws = new WebSocket('ws://' + server + ':' + port);

        ws.onopen = function() {
            document.getElementById('chat').innerHTML += '<p>Connection established!</p>';
        };

        ws.onmessage = function(event) {
            document.getElementById('chat').innerHTML += '<p>' + event.data + '</p>';
            document.getElementById('chat').scrollTop = document.getElementById('chat').scrollHeight;
        };

        ws.onclose = function() {
            document.getElementById('chat').innerHTML += '<p>Connection closed.</p>';
        };

        function sendMessage() {
            const msg = document.getElementById('message').value;
            if (msg) {
                ws.send(msg);
                document.getElementById('message').value = '';
                document.getElementById('chat').innerHTML += '<p align="right">' + msg + '</p>';
            }
        }
    </script>
</body>
</html>