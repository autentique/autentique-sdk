<?php

declare(strict_types= 1);

namespace Autentique\SDK\Utils;

class LoadEnv {
    private $dotenv;

    public function __construct() {
        $this->dotenv = \Dotenv\Dotenv::createImmutable(getcwd());
        $this->dotenv->load();
        $this->dotenv->required('AUTENTIQUE_SANDBOX')->isBoolean();
    }

    public function getUrl():string {
        return $_ENV["AUTENTIQUE_URL"];
    }

    public function getToken():string {
        return $_ENV["AUTENTIQUE_TOKEN"];
    }

    public function useSandbox():bool {
        return $_ENV["AUTENTIQUE_SANDBOX"] == "true" ? true : false;
    }
}