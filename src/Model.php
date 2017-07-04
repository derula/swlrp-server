<?php

namespace Incertitu\SWLRP;

abstract class Model extends Configurable {
    /** @var \PDO */
    private $connection;
    /** @var Config */
    private $config;
    public function __construct(\PDO $connection, Config $config) {
        $this->connection = $connection;
        $this->config = $config;
    }
    protected function getConnection(): \PDO {
        return $this->connection;
    }
    protected function getConfig(...$keys) {
        array_unshift($keys, 'Models', (new \ReflectionClass($this))->getShortName());
        return $this->config->get(...$keys);
    }
}
