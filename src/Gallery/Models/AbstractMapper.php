<?php

namespace Gallery\Models;

abstract class AbstractMapper
{
    abstract public function newEntity($row);

    public function newCollection($rows)
    {
        $newCollection = [];
        foreach ($rows as $row) {
            $entity = $this->newEntity($row);
            $newCollection[$entity->getTitle()] = $entity;
        }

        return $newCollection;
    }
}