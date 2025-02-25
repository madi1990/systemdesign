<?php

declare(strict_types=1);

class SplMinPriorityQueue extends SplPriorityQueue
{
    public function compare($a, $b): int
    {
        return $b <=> $a;
    }

    public function updatePriority($element): self
    {
        $queue = [];
        $this->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        $this->rewind();
        while ($this->valid()) {
            $current = $this->current();
            if ($current['data'] === $element) {
                // Skip the matched element
                $queue[] = [
                    'data' => $current['data'],
                    'priority' => ++$current['priority'],
                ];
            } else {
                $queue[] = [
                    'data' => $current['data'],
                    'priority' => $current['priority']
                ];
            }
            $this->next();
        }
        $newPriorityQueue = new self();
        foreach ($queue as $item) {
            $newPriorityQueue->insert($item['data'], $item['priority']);
        }

        return $newPriorityQueue;
    }
}

class LCU
{
    public function __construct(private int $capacity, private SplMinPriorityQueue $cacheList){
    }

    private function addElement(string $element): void {
        if ($this->cacheList->count() >= $this->capacity) {
            $this->cacheList->extract();
        }
        $this->cacheList->insert($element, 1);
    }

    private function isElementExisting(string $element): bool {
        $clonedList = clone $this->cacheList;
        foreach ($clonedList as $item) {
            if ($item === $element) {
                return true;
            }
        }
        return false;
    }

    public function accessElement(string $element): void {
        if ($this->isElementExisting($element)) {
            // Cache hit
            $this->cacheList = $this->cacheList->updatePriority($element);
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

$lcu = new LCU(3, new SplMinPriorityQueue());
$lcu->accessElement('A');
$lcu->accessElement('B');
$lcu->accessElement('C');
$lcu->accessElement('B');
//$lcu->outputCacheList();
$lcu->accessElement('D');
$lcu->accessElement('A');
$lcu->accessElement('A');
$lcu->accessElement('A');
$lcu->accessElement('C');
$lcu->outputCacheList();

//$queue = new SplPriorityQueue();
//$queue->insert('A', 3);
//$queue->insert('B', 2);
//$queue->insert('C', 1);
//
//// Clone the queue before iterating
//$clonedQueue = clone $queue;
//
//foreach ($clonedQueue as $item) {
//    echo $item . PHP_EOL; // Outputs: A, B, C
//}
//
//echo $queue->count();