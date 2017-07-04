<?php

namespace Incertitude\SWLRP;

class Application {
    /** @var array */
    private $get;
    /** @var array */
    private $post;
    /** @var Config */
    private $config;
    /** @var \PDO */
    private $pdo;
    /** @var Model[] */
    private $models = [];
    public function __construct(string $root, array $get=[], array $post=[]) {
        $this->get = $get;
        $this->post = $post;
        $this->config = new Config($root . '/config/config.yml');
        $this->pdo = new \PDO(
            $this->config->get('DB', 'dsn'),
            $this->config->get('DB', 'user'),
            $this->config->get('DB', 'password')
        );
    }
    public function getModel(string $name): Model {
        if (!isset($this->models[$name])) {
            $class = 'Models\\' . ucfirst($name);
            $this->models[$name] = new $class($pdo, $config);
        }
        return $this->models[$name];
    }
    public function getAction(string $name): Action {
        $class = 'Actions\\' . ucfirst($name);
        return new $class($this->post, $this->getModel($class::MODEL_NAME));
    }
    public function getView(string $name): View {
        $class = 'Views\\' . ucfirst($name);
        return new $class($this->get, $this->getModel($class::MODEL_NAME));
    }
}
