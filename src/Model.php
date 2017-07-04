<?php

namespace Incertitu\SWLRP;

abstract class Model {
    /** @var \PDO */
    private $connection;
    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }
    protected function getConnection(): \PDO {
        return $this->connection;
    }
}
