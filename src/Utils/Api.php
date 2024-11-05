<?php

namespace Autentique\AutentiqueSDK\Utils;

use CURLFile;
use Exception;

class Api {

    const ACCEPT_CONTENTS = ["json", "form"];

    private $url;

    private $timeout;

    public function __construct(string $url, int $timeout = 60) {
        if(empty($url)) {
            throw new Exception("Empty url", 400);
        }
        $this->url = $url;
        $this->timeout = $timeout;
    }

    public function connect(array $httpHeader, $fields): array {
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

    public function request(
        string $token, 
        string $query, 
        string $contentType, 
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
