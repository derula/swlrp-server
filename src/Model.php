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
    private function populateData(&$data, $level = 0) {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                if (0 === $level) {
                    $value += ['texts' => [], 'properties' => []];
                }
                $this->populateData($value, $level + 1);
                if (!is_numeric($key) && 1 !== $level) {
                    $value += [
                        'name' => $key,
                        'title' => ucwords($key),
                    ];
                }
            }
        }
    }
}
