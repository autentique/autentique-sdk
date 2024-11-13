<?php

declare(strict_types= 1);

namespace Autentique\SDK\Utils;

class Query {
    protected $resource;

    protected $file;

    public function __construct(string $resource) {
        $this->resource = __DIR__ . "/../../" . "queries/" . strtolower($resource);
    }

    public function query(string $file) {
        if(!is_dir($this->resource)) {
            throw new \Exception("The given resource directory does not exist");
        }

        if(!file_exists("$this->resource/$file") || empty($file)) {
            throw new \Exception("The file '$file' is not found", 400);
        }
        
        $query = file_get_contents("$this->resource/$file");
        return preg_replace("/[\n\r]/", "", $query);
    }

    public function setVariables(string|array $variables, int|string|array|bool $value, string $graphQuery) {
        if(is_array($variables) && is_array($value)) {
            for($i = 0; $i < count($variables); $i++) {
                $variable = "\$" . $variables[$i];
                $graphQuery = str_replace($variable, (string)$value[$i], $graphQuery);
            }
        } elseif(is_string($variables)) {
            $graphQuery = str_replace("\$$variables", (string)$value, $graphQuery);
        }

        return $graphQuery;
    }
}
