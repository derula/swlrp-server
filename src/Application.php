<?php

namespace Incertitude\SWLRP;

class Application {
    /** @var array */
    private $get;
    /** @var array */
    private $post;
    /** @var Config */
    private $config;
    /** @var Session */
    private $session;
    /** @var \PDO */
    private $pdo;
    /** @var Model[] */
    private $models = [];
    /** @var string */
    private $route = '';
    public function __construct(string $root, array $server=[], array $get=[], array $post=[]) {
        if (isset($_SERVER['REQUEST_URI'])) {
            $uri = parse_url($_SERVER['REQUEST_URI']);
            $uriParts = explode('/', substr($uri['path'] ?? '/', 1));
            $this->route = array_shift($uriParts) ?: '';
            $get = $uriParts + $get;
            $post = $uriParts + $post;
        }
        $this->get = $get;
        $this->post = $post;
        $this->config = new Config($root . '/config/config.yml');
        $this->pdo = new \PDO(
            $this->config->get('DB', 'dsn'),
            $this->config->get('DB', 'user'),
            $this->config->get('DB', 'password')
        );
        $this->session = new Session($this->getModel('Account'));
    }
    public function getRoute(): string {
        return $this->route;
    }
    public function getSession(): Session {
        return $this->session;
    }
    public function getModel(string $name): Model {
        if (!isset($this->models[$name])) {
            $class = __NAMESPACE__ . '\\Models\\' . ucfirst($name);
            $this->models[$name] = new $class($this->pdo, $this->config);
        }
        return $this->models[$name];
    }
    public function getAction(string $name): Action {
        $class = __NAMESPACE__ . '\\Actions\\' . ucfirst($name);
        return new $class($this->post, $this->getModel($class::MODEL_NAME));
    }
    public function getView(string $name): View {
        $class = __NAMESPACE__ . '\\Views\\' . ucfirst($name);
        return new $class($this->get, $this->getModel($class::MODEL_NAME));
    }
}
