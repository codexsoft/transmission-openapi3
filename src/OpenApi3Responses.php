<?php


namespace CodexSoft\Transmission\OpenApi3;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;

/**
 * Helper class
 */
class OpenApi3Responses
{
    public static function binary(string $description = 'Binary content', array $headers = [], string $contentType = 'application/octet-stream'): \cebe\openapi\spec\Response
    {
        $headersData = [];
        foreach ($headers as $name => $element) {
            if ($element instanceof AbstractElement) {
                $headersData[$name] = $element->toOpenApiParameter($name);
            } else {
                $headersData[$name] = $element;
            }
        }

        return new \cebe\openapi\spec\Response([
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
