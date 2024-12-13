<?php
error_reporting(E_ALL & ~E_DEPRECATED);
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1; // Количество подключенных клиентов, кроме отправителя
        echo sprintf('Connection %d sending message "%s" to %d other connection(s)' . "\n",
            $from->resourceId, $msg, $numRecv);

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send("Client {$from->resourceId} says: $msg");
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

use Ratchet\App;

$port = getenv('PORT') ?: 8081; // Получение порта из переменной среды
$app = new Ratchet\App('0.0.0.0', $port, '0.0.0.0');
$app->route('/chat', new ChatServer, ['*']); // Настройка маршрута
$app->run();
