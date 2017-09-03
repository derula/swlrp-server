<?php

namespace Incertitude\SWLRP;
use Symfony\Component\Yaml\Yaml;
use Incertitude\SWLRP\Exceptions\ConfigKeyMissing;

class Config {
    /** @var array */
    private $data;
    public function __construct(string $path) {
        $this->data = (array)Yaml::parse(file_get_contents($path));
    }
    public function get(...$keys) {
        try {
            return $this->find($keys);
        } catch (ConfigKeyMissing $ex) {
            return null;
        }
    }
    public function exists(...$keys) {
        try {
            $this->find($keys);
            return true;
        } catch (ConfigKeyMissing $ex) {
            return false;
        }
    }
    private function find(array $keys) {
        $result = $this->data;
        while (!empty($keys)) {
            $key = array_shift($keys);
            if ('*' === $key && is_array($result)) {
                $result = array_merge_recursive(...array_values(array_filter($result, 'is_array')));
                continue;
            }
            if (!isset($result[$key])) {
                throw new ConfigKeyMissing();
            }
            $result = $result[$key];
        }
        return $result;
    }
}
