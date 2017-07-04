<?php

namespace Incertitu\SWLRP;

abstract class IOComponent {
    const MODEL_NAME = 'Profile';
    /** @var array */
    private $data;
    /** @var Model */
    private $model;
    public function __construct(array $data, Model $model) {
        $this->data = $data;
        $this->model = $model;
    }
    protected function getData($key) {
        return $this->data[$key] ?? null;
    }
    protected function getModel(): Model {
        return $this->model;
    }
}
