<?php

declare(strict_types=1);

class LRU
{
    public function __construct(private int $capacity, private SplDoublyLinkedList $cacheList){
    }

    public function addElement(string $element): void {
        if ($this->cacheList->count() >= $this->capacity) {
            $this->cacheList->shift();
        }
        $this->cacheList->push($element);
    }

    public function outputCacheList(): void {
        echo $this->cacheList->count() . PHP_EOL;
        foreach ($this->cacheList as $element) {
            echo $element . PHP_EOL;
        }
    }
}

$lru = new LRU(3, new SplDoublyLinkedList());
$lru->addElement('A');
$lru->addElement('B');
$lru->addElement('C');
$lru->outputCacheList();
$lru->addElement('A');
$lru->outputCacheList();
$lru->addElement('D');
$lru->outputCacheList();