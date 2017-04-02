<?php

namespace Gallery\Models;

use Aura\Sql\ExtendedPdo;

abstract class AbstractModel
{
    public function __construct(ExtendedPdo $pdo)
    {
        $this->pdo = $pdo;
    }
}