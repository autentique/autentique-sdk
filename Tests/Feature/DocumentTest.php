<?php

namespace Autentique\SDK\Tests;

use Autentique\SDK\Documents;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{

    private $mockDoc;
    private $doc;

    public function setUp(): void
    {
        $this->mockDoc = [
            "document" => [
                "name" => "Teste SDK",
                "message" => "Teste de desc no SDK",
            ],
            "signers" => [
                [
                    "email" => "teste-sdk@tuamaeaquelaursa.com",
                    "action" => "SIGN",
                    "positions" => [
                        [
                            "x" => "40",
                            "y" => "80",
                            "z" => 1
                        ]
                    ],
                ]
            ],
            "file" => getcwd() . "/dummy.pdf"
        ];

        $this->doc = new Documents();
    }

    public function createDocumentTest(): void
    {
        $result = json_encode($this->doc->create($this->mockDoc));
        expect(json_decode($result))->toHaveProperty("data");
    }

}
