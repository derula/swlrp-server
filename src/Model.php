<?php

namespace Incertitude\SWLRP;

abstract class Model {
    /** @var \PDO */
    private $connection;
    /** @var Config */
    private $config;
    public function __construct(\PDO $connection, Config $config) {
        $this->connection = $connection;
        $this->config = $config;
    }
    public function getMetadata(): array {
        return $this->getConfig();
    }
    protected function getConnection(): \PDO {
        return $this->connection;
    }
    protected function getConfig(...$keys) {
        array_unshift($keys, 'Models', (new \ReflectionClass($this))->getShortName());
        return $this->config->get(...$keys);
    }
}
