<?php


namespace CodexSoft\Transmission\OpenApi3;


use cebe\openapi\spec\Components;
use cebe\openapi\spec\MediaType;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\Operation;
use cebe\openapi\spec\PathItem;
use cebe\openapi\spec\Paths;
use cebe\openapi\spec\RequestBody;
use cebe\openapi\spec\Response;
use CodexSoft\Transmission\Schema\Elements\AbstractElement;
use CodexSoft\Transmission\Schema\Elements\JsonElement;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Routing\RouteCollection;

class OpenApi3SchemaGenerator
{
    private LoggerInterface $logger;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param RouteCollection $routes
     * @param OpenApi|array|null $openApi
     *
     * @return OpenApi
     * @throws \CodexSoft\Transmission\Schema\Exceptions\InvalidJsonSchemaException
     * @throws \cebe\openapi\exceptions\TypeErrorException
     */
    public function generate(RouteCollection $routes, $openApi = null): OpenApi
    {
        $defaultOpenApi = [
            'openapi' => '3.0.2',
            'info' => [
                'title' => '',
                'version' => '1.0.0',
            ],
            'paths' => [],
            'components' => [],
        ];

        if (\is_array($openApi)) {
            $openApi = new OpenApi(\array_merge($defaultOpenApi, $openApi));
        } elseif ($openApi instanceof OpenApi) {
            if ($openApi->components === null) {
                $openApi->components = new Components([]);
            }
            if ($openApi->paths === null) {
                $openApi->paths = new Paths([]);
            }
        } elseif ($openApi === null) {
            $openApi = new OpenApi($defaultOpenApi);
        } else {
            throw new \Exception('OpenApi is '.\gettype($openApi).' but '.OpenApi::class.'|array|null expected');
        }

        foreach ($routes as $routeItem) {
            $endpointClass = $routeItem->getDefault('_controller');

            /** @var OpenApi3OperationInterface $endpointClass */

            $parameters = [];

            $requestBody = new RequestBody([
                //'description' => Type::STRING,
                'content' => [
                    'application/json' => new MediaType([
                        'schema' => (new JsonElement($endpointClass::getOpenApiBodyParametersSchema()))->toOpenApiSchema(),
                    ]),
                ],
                'required' => true,
            ]);

            foreach ($endpointClass::getOpenApiPathParametersSchema() as $key => $element) {
                $parameters[$key] = $element->toOpenApiParameter($key, 'path');
            }

            foreach ($endpointClass::getOpenApiQueryParametersSchema() as $key => $element) {
                $parameters[$key] = $element->toOpenApiParameter($key, 'query');
            }

            foreach ($endpointClass::getOpenApiHeaderParametersSchema() as $key => $element) {
                $parameters[$key] = $element->toOpenApiParameter($key, 'header');
            }

            foreach ($endpointClass::getOpenApiCookieParametersSchema() as $key => $element) {
                $parameters[$key] = $element->toOpenApiParameter($key, 'cookie');
            }

            $routePath = $routeItem->getPath();

            $responses = [];
            foreach ($endpointClass::getOpenApiResponses() as $httpCode => $responsesData) {
                if ($responsesData instanceof Response) {
                    $responses[(string) $httpCode] = $responsesData;
                    continue;
                }

                if ($responsesData instanceof AbstractElement) {

                    $responses[(string) $httpCode] = new \cebe\openapi\spec\Response([
                        'description' => $responsesData->getLabel() ?: 'Default response',
                        'content' => [
                            'application/json' => [
                                'schema' => $responsesData->toOpenApiSchema(),
                            ],
                        ],
                    ]);
                    continue;
                }

                $this->logger->warning($routeItem->getPath().' response for HTTP code '.$httpCode.' specification is invalid');
            }

            $operation = new Operation([
                'tags' => $endpointClass::getOpenApiTags(),
                'parameters' => $parameters,
                'requestBody' => $requestBody,
                'responses' => $responses,
                'description' => $endpointClass::getOpenApiDescription(),
                'summary' => $endpointClass::getOpenApiSummary() ?: $routePath,
            ]);

            $path = $openApi->paths->getPath($routePath);
            if ($path === null) {
                $path = new PathItem([]);
                $openApi->paths->addPath($routePath, $path);
            }

            foreach ($routeItem->getMethods() as $routeMethod) {
                $property = \mb_strtolower($routeMethod);
                $path->$property = $operation;
            }
        }

        return $openApi;
    }
}
