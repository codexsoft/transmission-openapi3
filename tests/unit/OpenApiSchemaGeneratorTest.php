<?php

namespace CodexSoft\Transmission\OpenApi3;

use PHPUnit\Framework\TestCase;

class OpenApiSchemaGeneratorTest extends TestCase
{

    public function testGenerate()
    {
        (new OpenApi3SchemaGenerator())->generate();
    }
}
