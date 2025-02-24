<?php

declare(strict_types=1);

class Hashing
{
    private array $nodes;
    public function __construct(
        private readonly int $numberOfReplicas = 3,
    ) {
    }

    public function addNode(string $node): void {
        for ($i = 0; $i < $this->numberOfReplicas; $i++) {
            $hash = $this->hash($node . $i);
            $this->nodes[$hash] = $node;
        }
        ksort($this->nodes);
    }

    public function removeNode(string $node): void {
        for ($i = 0; $i < $this->numberOfReplicas; $i++) {
            $hash = $this->hash($node . $i);
            unset($this->nodes[$hash]);
        }
    }

    public function getNode(string $key): null|string {
        if (empty($this->nodes)) {
            return null;
        }
        $hash = $this->hash($key);
        foreach ($this->nodes as $nodeHash => $node) {
            if ($nodeHash > $hash) {
                return $node;
            }
        }
        return reset($this->nodes);
    }

    public function getNodes(): array {
        return $this->nodes;
    }

    private function hash(string $key): int {
        return hexdec(substr(md5($key), 0, 16)) % PHP_INT_MAX;
    }
}

$hashRing = new Hashing();

$hashRing->addNode("Server A");
$hashRing->addNode("Server B");
$hashRing->addNode("Server C");
$hashRing->addNode("Server D");

$keys = range(1, 100);
$users = array_map(static fn($key) => "User$key", $keys);

foreach ($users as $user) {
    echo "User '$user' is mapped to " . $hashRing->getNode($user) . "\n";
}

// Removing a node
$hashRing->removeNode("Server D");
$hashRing->removeNode("Server C");

echo "\nAfter removing 'Server D':\n";
foreach ($users as $user) {
    echo "User '$user' is now mapped to " . $hashRing->getNode($user) . "\n";
}