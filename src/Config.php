<?php

namespace Incertitu\SWLRP;
use Symphony\Component\Yaml\Yaml;

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
            if (!isset($result[$key])) {
                return null;
            }
            $result = $result[$key];
        }
        return $result;
    }
}
