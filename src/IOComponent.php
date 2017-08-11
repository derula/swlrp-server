<?php

namespace Incertitude\SWLRP;

abstract class IOComponent {
    const MODEL_NAME = 'Profile';
    /** @var array */
    private $data;
    /** @var Model */
    private $model;
    public function __construct(array $data, Application $application) {
        $this->data = $data;
        $this->model = $application->getModel(static::MODEL_NAME);
    }
    protected function getData($key=null) {
        return isset($key) ? $this->data[$key] ?? null : $this->data;
    }
    protected function getIntData($key, int $fallback=0): int {
        return (int)($this->getData($key) ?? $fallback);
    }
    protected function getNameData(): array {
        $names = [];
        foreach (['first', 'nick', 'last'] as $type) {
            $names[$type] = ucwords($this->getData($type) ?: '');
        }
        return $names;
    }
    protected function getModel(): Model {
        return $this->model;
    }
    protected function &iterateMetaData(array &$output=null): \Generator {
        $output = $this->getModel()->getMetadata();
        foreach ($output as &$section) {
            foreach (['properties', 'texts'] as $key) {
                foreach ($section[$key] as &$prop) {
                    yield $key => $prop;
                }
            }
        }
    }
}
