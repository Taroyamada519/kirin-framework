<?php
namespace App\Core;

class Request {
    private $params;
    private $queryParams;
    private $body;
    private $method;
    private $path;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->queryParams = $_GET;
        $this->body = $this->getRequestBody();
        $this->params = [];
    }

    private function getRequestBody() {
        $body = file_get_contents('php://input');
        return json_decode($body, true) ?? [];
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPath() {
        return $this->path;
    }

    public function getBody() {
        return $this->body;
    }

    public function getQueryParams() {
        return $this->queryParams;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function getParams() {
        return $this->params;
    }

    public function getParam($name) {
        return $this->params[$name] ?? null;
    }
}
