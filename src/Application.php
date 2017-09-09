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
    private $root = '', $route = '';
    public function __construct(string $root, array $server=[], array $get=[], array $post=[]) {
        $this->root = $root;
        if (isset($_SERVER['REQUEST_URI'])) {
            $uri = parse_url(urldecode($_SERVER['REQUEST_URI']));
            $uriParts = array_filter(explode('/', substr($uri['path'] ?? '/', 1)));
            $this->route = array_shift($uriParts) ?: '';
            $get = $uriParts + $get;
            $post = $uriParts + $post;
        }
        $this->get = array_map('trim', $get);
        $this->post = array_map('trim', $post);
        $this->config = new Config($root . '/config/config.yml');
        $this->pdo = new \PDO(
            $this->config->get('DB', 'dsn'),
            $this->config->get('DB', 'user'),
            $this->config->get('DB', 'password')
        );
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $this->session = new Session($this->getModel('Account'), !empty($_SERVER['HTTPS']));
    }
    public function getRoot(): string {
        return $this->root;
    }
    public function getRoute(): string {
        return $this->route;
    }
    public function getConfig(): Config {
        return $this->config;
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
        return new $class($this->post, $this);
    }
    public function getView(string $name): View {
        $class = __NAMESPACE__ . '\\Views\\' . ucfirst($name);
        return new $class($this->get, $this);
    }
}
