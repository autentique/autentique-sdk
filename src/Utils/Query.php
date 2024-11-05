<?php

namespace Autentique\AutentiqueSDK\Utils;

class Query {
    protected $resource;

    protected $file;

    public function __construct(string $resource) {
        $this->resource = __DIR__ . "/../../" . "queries/" . strtolower($resource);
    }

    public function query(string $file) {
        if(!file_exists("$this->resource") || empty($file)) {
            throw new \Exception("The file '$file' is not found", 400);
        }
        
        $query = file_get_contents("$this->resource/$file");
        return preg_replace("/[\n\r]/", "", $query);
    }

    public function setVariables($variables, $value, string $graphQuery) {
        if(is_array($variables) && is_array($value)) {
            for($i = 0; $i < count($variables); $i++) {
                $variable = "\$" . $variable[$i];
                $graphQuery = str_replace($variable, $value[$i], $graphQuery);
            }
        } elseif(is_string($variables)) {
            $graphQuery = str_replace("\$$variables", $value, $graphQuery);
        }

        return $graphQuery;
    }
}
