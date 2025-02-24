<?php

declare(strict_types=1);

class LRU
{
    public function __construct(private int $capacity, private SplDoublyLinkedList $cacheList){
    }

    private function addElement(string $element): void {
        if ($this->cacheList->count() >= $this->capacity) {
            $this->cacheList->shift();
        }
        $this->cacheList->push($element);
    }

    private function isElementExisting(string $element, bool $removeUponFound = false): bool {
        $this->cacheList->rewind();
        while ($this->cacheList->valid()) {
            if ($this->cacheList->current() === $element) {
                if ($removeUponFound) {
                    $this->cacheList->offsetUnset($this->cacheList->key());
                }
                return true;
            }
            $this->cacheList->next();
        }
        return false;
    }

    public function accessElement(string $element): void {
        if ($this->isElementExisting($element, true)) {
            // Cache hit
            $this->addElement($element);
        } else {
            // Cache miss
            $this->addElement($element);
        }
    }

    public function outputCacheList(): void {
        echo $this->cacheList->count() . PHP_EOL;
        foreach ($this->cacheList as $element) {
            echo $element . PHP_EOL;
        }
    }
}

$lru = new LRU(3, new SplDoublyLinkedList());
$lru->accessElement('A');
$lru->accessElement('B');
$lru->accessElement('C');
$lru->outputCacheList();
$lru->accessElement('B');
$lru->outputCacheList();
$lru->accessElement('D');
$lru->outputCacheList();