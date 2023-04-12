<?php

namespace App\Controller\Api\v1;

use App\Service\SearchTermService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/words', name: 'api_v1_words_')]
class WordController extends AbstractController
{
    #[Route('/')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to Word!',
        ]);
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function searchAction(
        Request $request,
        ValidatorInterface $validator,
        SearchTermService $searchTermService
    ): JsonResponse
    {
        $termConstraint = new Assert\NotBlank();
        $termConstraint->message = 'Parameter term must be present and can not be empty.';
        $term = $request->get('term');

        $errors = $validator->validate(
            trim($term),
            $termConstraint
        );

        if ($errors->count()) {
            return $this->json([
                'message' => $errors[0]->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $word = $searchTermService->getResult($term);
        } catch (\Exception $e) {
            return $this->json([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getCode());
        }

        return $this->json([
            'term' => $term,
            'score' => $word->getPopularityScore()
        ], Response::HTTP_OK);
    }
}
