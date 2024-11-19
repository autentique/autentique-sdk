<?php

declare(strict_types= 1);

namespace Autentique\SDK\Utils;

class LoadEnv {
    /**
     * @var \Dotenv\Dotenv
     */
    private $dotenv;

    public function __construct() {
        $this->dotenv = \Dotenv\Dotenv::createImmutable(getcwd());
        $this->dotenv->load();
        $this->dotenv->required('AUTENTIQUE_SANDBOX')->isBoolean();
    }
    /**
     * The API URL
     * @return string
     */
    public function getUrl():string {
        return $_ENV["AUTENTIQUE_URL"];
    }

    /**
     * The API token
     * @return string
     */
    public function getToken():string {
        return $_ENV["AUTENTIQUE_TOKEN"];
    }

    /**
     * The sandbox mode, if set to true,
     * all the documents will be created in sandbox
     * @return bool
     */
    public function useSandbox():bool {
        return $_ENV["AUTENTIQUE_SANDBOX"] == "true" ? true : false;
    }
}