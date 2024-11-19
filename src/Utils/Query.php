<?php

declare(strict_types= 1);

namespace Autentique\SDK\Utils;

class Query {
    /**
     * @var string
     */
    protected $resource;

    /**
     * @var string
     */
    protected $file;

    /**
     * @param string $resource
     */
    public function __construct(string $resource) {
        $this->resource = __DIR__ . "/../../" . "queries/" . strtolower($resource);
    }

    /**
     * @param string $file
     * @throws \Exception
     * @return array|string|null
     */
    public function query(string $file): array|string|null {
        if(!is_dir($this->resource)) {
            throw new \Exception("The given resource directory does not exist");
        }

        if(!file_exists("$this->resource/$file") || empty($file)) {
            throw new \Exception("The file '$file' is not found", 400);
        }
        
        $query = file_get_contents("$this->resource/$file");
        return preg_replace("/[\n\r]/", "", $query);
    }

    /**
     * @param string|array $variables
     * @param int|string|array|bool $value
     * @param string $graphQuery
     * @return string
     */
    public function setVariables(string|array $variables, int|string|array|bool $value, string $graphQuery): string {
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
