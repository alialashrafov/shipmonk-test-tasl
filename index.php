<?php

class Node
{
    public mixed $val;
    public ?Node $next;

    public function __construct(mixed $val)
    {
        $this->val = $val;
        $this->next = null;
    }
}

class SortedLinkedList
{
    private ?Node $first = null;
    private ?string $allowedType = null; // 'int' or 'string'

    public function insert(mixed $item): void
    {
        if (!is_int($item) && !is_string($item)) {
            throw new InvalidArgumentException("Only int or string allowed.");
        }

        if ($this->allowedType === null) {
            $this->allowedType = gettype($item);
        } elseif (gettype($item) !== $this->allowedType) {
            throw new InvalidArgumentException("Only {$this->allowedType} values allowed.");
        }

        $node = new Node($item);

        // if list is empty or new item is smaller than the first one
        if ($this->first === null || $this->compare($item, $this->first->val) < 0) {
            $node->next = $this->first;
            $this->first = $node;
            return;
        }

        $ptr = $this->first;
        while ($ptr->next !== null && $this->compare($ptr->next->val, $item) < 0) {
            $ptr = $ptr->next;
        }

        $node->next = $ptr->next;
        $ptr->next = $node;
    }

    public function remove(mixed $val): bool
    {
        if ($this->first === null) return false;

        if ($this->first->val === $val) {
            $this->first = $this->first->next;
            return true;
        }

        $cur = $this->first;
        while ($cur->next !== null && $cur->next->val !== $val) {
            $cur = $cur->next;
        }

        if ($cur->next === null) return false;

        $cur->next = $cur->next->next;
        return true;
    }

    public function has(mixed $val): bool
    {
        $cur = $this->first;
        while ($cur !== null) {
            if ($cur->val === $val) return true;
            $cur = $cur->next;
        }
        return false;
    }

    public function asArray(): array
    {
        $out = [];
        $cur = $this->first;
        while ($cur !== null) {
            $out[] = $cur->val;
            $cur = $cur->next;
        }
        return $out;
    }

    private function compare(mixed $a, mixed $b): int
    {
        // not super robust, but does the job
        if ($this->allowedType === 'int') {
            return $a <=> $b;
        }

        if ($this->allowedType === 'string') {
            return strcmp($a, $b);
        }

        throw new LogicException("Unsupported type: " . $this->allowedType);
    }
}
