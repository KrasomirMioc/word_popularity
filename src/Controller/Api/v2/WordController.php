<?php

namespace App\Controller\Api\v2;

use App\Service\SearchTermService;
use Floor9design\JsonApiFormatter\Exceptions\JsonApiFormatterException;
use Floor9design\JsonApiFormatter\Models\JsonApiFormatter;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v2/words', name: 'api_v2_words_')]
class WordController extends BaseController
{
    #[Route('/')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to Word APIv2!',
        ]);
    }

    /**
     * @throws JsonApiFormatterException
     *
     * @OA\Get(
     *     description="Get popularity score for searched word/term"
     * )
     *
     * @OA\Response(
     *     response="200",
     *     description="Sucess",
     *     content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                   @OA\Property(
     *                      property="data",
     *                      type="object",
     *                      @OA\Property(
     *                          property="attribute",
     *                          type="object",
     *                          @OA\Property(
     *                              property="score",
     *                              type="float",
     *                              description="Calculated word popularity"
     *                          ),
     *                          @OA\Property(
     *                              property="term",
     *                              type="string",
     *                              description="Searched word/term"
     *                          ),
     *                          @OA\Property(
     *                              property="positiveCount",
     *                              type="int",
     *                              description="Count of positive occasion"
     *                          ),
     *                          @OA\Property(
     *                              property="negativeCount",
     *                              type="int",
     *                              description="Count of negative occasion"
     *                          ),
     *                          @OA\Property(
     *                              property="totalCount",
     *                              type="int",
     *                              description="Count of total occasion"
     *                          ),
     *                          @OA\Property(
     *                              property="source",
     *                              type="string",
     *                              description="Source of data"
     *                          ),
     *                          @OA\Property(
     *                              property="created_at",
     *                              type="datetime",
     *                              description="Date and time of creation"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="data_resource_meta",
     *                          type="string",
     *                          default="null"
     *                      ),
     *                      @OA\Property(
     *                          property="id",
     *                          type="int"
     *                      ),
     *                      @OA\Property(
     *                          property="relationship",
     *                          type="string",
     *                          default="null"
     *                      ),
     *                      @OA\Property(
     *                          property="type",
     *                          type="string"
     *                      ),
     *                  ),
     *                  @OA\Property(
     *                      property="meta",
     *                      type="object",
     *                  ),
     *                  @OA\Property(
     *                      property="jsonapi",
     *                      type="object",
     *                      @OA\Property(
     *                          property="version",
     *                          type="string",
     *                          default="1.1"
     *                      )
     *                  )
     *              )
     *          )
     *     }
     * )
     *
     * @OA\Response(
     *     response="422",
     *     description="Validation error",
     *     content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="errors",
     *                      type="array",
     *                      description="Array of errors",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="links",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="status",
     *                              type="string",
     *                              description="status code"
     *                          ),
     *                          @OA\Property(
     *                              property="code",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="title",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="detail",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="source",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="meta",
     *                              type="string"
     *                          ),
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="meta",
     *                      type="object",
     *                  ),
     *                  @OA\Property(
     *                      property="jsonapi",
     *                      type="object",
     *                      @OA\Property(
     *                          property="version",
     *                          type="string",
     *                          default="1.1"
     *                      )
     *                  )
     *              )
     *          )
     *     }
     * )
     *
     * @OA\Response(
     *     response="401",
     *     description="Unauthenticated. Token missing or not valid.",
     *     content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="errors",
     *                      type="array",
     *                      description="Array of errors",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="links",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="status",
     *                              type="string",
     *                              description="status code"
     *                          ),
     *                          @OA\Property(
     *                              property="code",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="title",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="detail",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="source",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="meta",
     *                              type="string"
     *                          ),
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="meta",
     *                      type="object",
     *                  ),
     *                  @OA\Property(
     *                      property="jsonapi",
     *                      type="object",
     *                      @OA\Property(
     *                          property="version",
     *                          type="string",
     *                          default="1.1"
     *                      )
     *                  )
     *              )
     *          )
     *     }
     * )
     *
     * @OA\Parameter(
     *     name="term",
     *     in="query",
     *     required=false,
     *     description="Term/word to search for",
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Tag(name="Search for word")
     *
     */
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function searchAction(
        Request $request,
        ValidatorInterface $validator,
        SearchTermService $searchTermService
    ): JsonResponse
    {
        $term = $request->get('term');
        $errors = $this->getErrors($term, $validator);

        $jsonApiFormatter = new JsonApiFormatter();

        if ($errors->count()) {

            $response = $this->handleJsonApiErrorResponse($errors, $jsonApiFormatter);

            return $this->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $word = $searchTermService->getResult($term);

        } catch (\Exception $e) {

            $response = $this->handleJsonApiErrorResponse($e, $jsonApiFormatter);

            return $this->json($response, $e->getCode());
        }

        $response = $this->handleJsonApiSuccessResponse($word, $jsonApiFormatter);

        return $this->json($response, Response::HTTP_OK);
    }
}