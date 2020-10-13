<?php

namespace CodexSoft\Transmission\OpenApi3\OpenApiSchemaGeneratorTest;

use CodexSoft\Transmission\Schema\Accept;
use CodexSoft\Transmission\Schema\Contracts\JsonSchemaInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("pets")
 */
class PetsAction implements JsonSchemaInterface
{
    /**
     * @inheritDoc
     */
    public static function bodyInputSchema(): array
    {
        return [
            'name' => Accept::string()->optional(),
        ];
    }

    public static function alternativeResponses(): array
    {
        return [
            200 => [
                'message' => Accept::string(),
            ],
            //Response::HTTP_BAD_REQUEST => 'test override',
            //Response::HTTP_NOT_FOUND => Accept::json([
            //    'message' => Accept::string(),
            //]),
        ];
    }

    //public function handle(array $data, array $extraData = []): Response
    //{
    //    return new JsonResponse(['data' => []]);
    //}

    public static function getOpenApiTags(): array
    {
        return ['Pets'];
    }

    /**
     * @inheritDoc
     */
    public static function bodyOutputSchema(): array
    {
        return [
            'pets' => Accept::collection(PetData::class),
            'pagination' => Accept::json([
                'perPage' => Accept::integer(),
                'currentPage' => Accept::integer(),
                'totalCount' => Accept::integer(),
                'totalPages' => Accept::integer(),
            ]),
        ];
    }
}
