<?php

namespace App\Controller\Api\v2;

use App\Service\SearchTermService;
use Floor9design\JsonApiFormatter\Exceptions\JsonApiFormatterException;
use Floor9design\JsonApiFormatter\Models\DataResource;
use Floor9design\JsonApiFormatter\Models\JsonApiFormatter;
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