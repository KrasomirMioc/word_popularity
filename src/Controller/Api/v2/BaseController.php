<?php

namespace App\Controller\Api\v2;

use App\Entity\Word;
use Floor9design\JsonApiFormatter\Exceptions\JsonApiFormatterException;
use Floor9design\JsonApiFormatter\Models\DataResource;
use Floor9design\JsonApiFormatter\Models\Error;
use Floor9design\JsonApiFormatter\Models\JsonApiFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    /**
     * @throws JsonApiFormatterException
     */
    public function handleJsonApiErrorResponse($errors, JsonApiFormatter $jsonApiFormatter): array
    {
        $jsonApiError = new Error();
        if ($errors instanceof ConstraintViolationList) {
            $jsonApiError->setId($errors[0]->getCode());
            $jsonApiError->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $jsonApiError->setCode($errors[0]->getCode());
            $jsonApiError->setTitle($errors[0]->getMessage());
        }

        if ($errors instanceof \Exception) {
            $jsonApiError->setStatus($errors->getCode());
            $jsonApiError->setTitle($errors->getMessage());
            $jsonApiError->setDetail(
                ($this->getParameter('kernel.environment') == 'dev')
                    ? $errors->getTraceAsString()
                    : null
            );
        }

        return $jsonApiFormatter->errorResponseArray([$jsonApiError]);
    }

    /**
     * @throws JsonApiFormatterException
     */
    public function handleJsonApiSuccessResponse(Word $word, JsonApiFormatter $jsonApiFormatter): array
    {
        $dataResource = new DataResource(
            $word->getId(),
            $word->getClassName(),
            [
                'score' => $word->getPopularityScore(),
                'term' => $word->getName(),
                'positiveCount' => $word->getPositiveCount(),
                'negativeCount' => $word->getNegativeCount(),
                'totalCount' => $word->getTotalCount(),
                'source' => $word->getSource(),
                'createdAt' => $word->getCreatedAt()->format('Y-m-d H:i:s')
            ]
        );

        return $jsonApiFormatter->dataResourceResponseArray($dataResource);
    }

    public function getErrors(string $term, ValidatorInterface $validator): ConstraintViolationListInterface
    {
        $termConstraint = new Assert\NotBlank();
        $termConstraint->message = 'Parameter term must be present and can not be empty.';

        return $validator->validate(
            trim($term),
            $termConstraint
        );
    }
}