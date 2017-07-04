<?php

namespace Incertitu\SWLRP;

abstract class IOComponent {
    /** @var array */
    private $data;
    /** @var Model */
    private $model;
    /** @var Config */
    private $config;
    public function __construct(array $data, Model $model, Config $config) {
        $this->data = $data;
        $this->model = $model;
        $this->config = $config;
    }
    protected function getData($key) {
        return $this->data[$key] ?? null;
    }
    protected function getModel(): Model {
        return $this->model;
    }
    protected function getConfig(...$keys) {
        return $this->config->get(...$keys);
    }
}
