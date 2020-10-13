<?php


namespace CodexSoft\Transmission\OpenApi3\OpenApiSchemaGeneratorTest;


use CodexSoft\Transmission\Schema\Accept;
use CodexSoft\Transmission\Schema\Contracts\JsonSchemaInterface;

class PetData implements JsonSchemaInterface
{
    /**
     * @inheritDoc
     */
    public static function createSchema(): array
    {
        return [
            'id' => Accept::id('ID питомца'),
            'name' => Accept::string()->notBlank(),
        ];
    }
}
