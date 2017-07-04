<?php

namespace Incertitude\SWLRP;
use Symfony\Component\Yaml\Yaml;

class Config {
    /** @var array */
    private $data;
    public function __construct(string $path) {
        $this->data = (array)Yaml::parse($path);
    }
    public function get(...$keys) {
        $result = $this->data;
        while (!empty($keys)) {
            $key = array_shift($keys);
            if ('*' === $key && is_array($result)) {
                $result = array_merge_recursive(...array_values(array_filter($result, 'is_array')));
                continue;
            }
            if (!isset($result[$key])) {
                return null;
            }
            $result = $result[$key];
        }
        return $result;
    }
}
