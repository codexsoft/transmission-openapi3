<?php


namespace CodexSoft\Transmission\OpenApi3;


use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\PathItem;
use Symfony\Component\Routing\RouteCollection;

class OpenApi3SchemaGenerator
{
    public function generate(RouteCollection $routeCollection, ?OpenApi $openApi = null): OpenApi
    {
        $openApi = $openApi ?? new OpenApi([]);

        foreach ($routeCollection as $routeItem) {
            $routePath = $routeItem->getPath();
            if (!$openApi->paths->hasPath($routePath)) {
                $openApi->paths->addPath($routePath, new PathItem([]));
            }
            $path = $openApi->paths->getPath($routePath);
            //$path->post->
        }

        return $openApi;
    }
}
