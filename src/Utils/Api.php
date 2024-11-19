<?php

declare(strict_types= 1);

namespace Autentique\SDK\Utils;

use CURLFile;
use Exception;

class Api {
    /**
     * Allowed content-types
     * @var array
     */
    const ACCEPT_CONTENTS = ["json", "form"];

    /**
     * The API URL
     * @var string
     */
    private $url;

    /**
     * The timeout
     * @var int
     */
    private $timeout;

    /** @param int $timeout */
    public function __construct(int $timeout = 60) {
        $env = new LoadEnv();
        $this->url = $env->getUrl();
        $this->timeout = $timeout;
    }

    /**
     * Function that connects with the API
     * @param array $httpHeader
     * @param mixed $fields
     * @throws \Exception
     * @return array
     */
    public function connect(array $httpHeader, array|string $fields): array {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_HTTPHEADER => $httpHeader,
                CURLOPT_TIMEOUT => $this->timeout,
            ]
        );

        $response = curl_exec($curl);

        $errorNo = curl_errno($curl);

        if($response == "[]") {
            throw new Exception("No results", 404);
        }

        if($errorNo) {
            throw new Exception(curl_error($curl));
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * @param string $token
     * @param string $contentType
     * @param string $query
     * @throws \Exception
     * @return void
     */
    private function validateParams(string $token, string $contentType, string $query): void {
        if(empty($token)) {
            throw new Exception("Token field is empty",400);
        }
        if(!in_array($contentType, self::ACCEPT_CONTENTS)) {
            throw new Exception("Content type doesn't exists",400);
        }

        if(empty($query)) {
            throw new Exception("Query is empty",400);
        }

        if(!filter_var($this->url, FILTER_VALIDATE_URL)) {
            throw new Exception("URL field is empty",400);
        }
    }

    /**
     * Function that makes the request 
     * for the API and return the results
     * @param string $token
     * @param string $query
     * @param string $contentType
     * @param string $pathFile
     * @return array
     */
    public function request(
        string $token, 
        string $query, 
        string $contentType = "json", 
        string $pathFile = null    
    ): array {
        $this->validateParams($token, $contentType, $query);

        $httpHeader = ["Authorization: Bearer {$token}"];

        $fields = "{\"query\": $query}";

        if($contentType == "json") {
            $contentType = "Content-Type: application/json";
        } else {
            $contentType = "Content-Type: multipart/form-data";
            $fields = [
                "operations" => $fields,
                "map" => "{\"file\": [\"variables.file\"]}",
                "file" => new CURLFile($pathFile),
            ];
        }

        array_push($httpHeader, $contentType);

        return $this->connect($httpHeader, $fields);
    }
}
