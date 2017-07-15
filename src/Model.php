<?php

namespace Incertitude\SWLRP;

abstract class Model {
    /** @var \PDO */
    private $connection;
    /** @var Config */
    private $config;
    /** @var array */
    private $meta;
    public function __construct(\PDO $connection, Config $config) {
        $this->connection = $connection;
        $this->config = $config;
    }
    public function getMetadata(): array {
        if (!isset($this->meta)) {
            $data = $this->getConfig();
            $this->populateData($data);
            $this->meta = $data;
        }
        return $this->meta;
    }
    protected function getConnection(): \PDO {
        return $this->connection;
    }
    protected function getConfig(...$keys) {
        array_unshift($keys, 'Models', (new \ReflectionClass($this))->getShortName());
        return $this->config->get(...$keys);
    }
    private function populateData(&$data) {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $this->populateData($value);
                if (!is_numeric($key) && !in_array($key, ['texts', 'properties'])) {
                    $value += [
                        'name' => $key,
                        'title' => ucwords($key),
                    ];
                }
            }
        }
    }
}
