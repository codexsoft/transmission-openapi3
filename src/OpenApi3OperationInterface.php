<?php


namespace CodexSoft\Transmission\OpenApi3;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;

interface OpenApi3OperationInterface
{
    /**
     * Expected request cookie parameters
     * @return AbstractElement[]
     */
    public static function getOpenApiCookieParametersSchema(): array;

    /**
     * Expected request query parameters
     * @return AbstractElement[]
     */
    public static function getOpenApiQueryParametersSchema(): array;

    /**
     * Expected request path parameters
     * Because path parameters are always strings, schema elements should not be strict for
     * non-string types.
     * @return AbstractElement[]
     */
    public static function getOpenApiPathParametersSchema(): array;

    /**
     * Expected request body parameters
     * @return AbstractElement[]
     */
    public static function getOpenApiHeaderParametersSchema(): array;

    /**
     * Expected request body parameters (JSON for example)
     * @return AbstractElement[]
     */
    public static function getOpenApiBodyParametersSchema(): array;

    /**
     * OpenAPI operation tags
     * @return string[]
     */
    public static function getOpenApiTags(): array;

    /**
     * OpenAPI operation summary
     * @return string
     */
    public static function getOpenApiSummary(): string;

    /**
     * OpenAPI operation description
     * @return string
     */
    public static function getOpenApiDescription(): string;

    /**
     * OpenAPI operation responses
     * Use AbstractElement for JSON responses, use cebe Response in more complex cases
     * @return AbstractElement[]|\cebe\openapi\spec\Response[]
     */
    public static function getOpenApiResponses(): array;
}
