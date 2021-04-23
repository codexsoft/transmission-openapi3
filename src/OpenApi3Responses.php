<?php


namespace CodexSoft\Transmission\OpenApi3;


use cebe\openapi\spec\Response as OpenApiResponse;
use CodexSoft\Transmission\OpenApi3\OpenApi3Generator;
use CodexSoft\Transmission\Schema\Elements\AbstractElement;

/**
 * Helper class
 */
class OpenApi3Responses
{
    public static function binary(
        string $description = 'Binary content',
        array $headers = [],
        string $contentType = 'application/octet-stream',
        ?OpenApi3Generator $factory = null,
    ): OpenApiResponse
    {
        $factory = $factory ?: new OpenApi3Generator();

        $headersData = [];
        foreach ($headers as $name => $element) {
            if ($element instanceof AbstractElement) {
                $headersData[$name] = $factory->toParameter($element, $name);
            } else {
                $headersData[$name] = $element;
            }
        }

        return new OpenApiResponse([
            'description' => $description,
            'content' => [
                $contentType => [
                    'schema' => [
                        'type' => 'string',
                        'format' => 'binary',
                    ],
                ],
            ],
            'headers' => $headersData,
        ]);
    }
}
