<?php

namespace App\Service;

use App\Interface\SearchProviderInterface;
use App\Interface\WordRepositoryInterface;

class SearchTermService
{

    public function __construct(
        private readonly WordRepositoryInterface $wordRepository,
        private readonly SearchProviderInterface $searchProvider
    )
    {
    }

    public function getResult(string $term): ?float
    {
        $providerName = $this->searchProvider->getProviderName();
        $word = $this->wordRepository->findOneByName($term, $providerName);

        if (!$word) {

            $counts = $this->searchProvider->getSearchResult($term);
            $popularityScore = $this->calculatePopularity($counts);

            $word = $this->wordRepository->create([
                'name' => $term,
                'source' => $providerName,
                'score' => $popularityScore,
                'positive_count' => $counts['positiveCount'],
                'negative_count' => $counts['negativeCount'],
                'total_count' => $counts['totalCount']
            ]);
        }

        return $word?->getPopularityScore() ?? 0.00;
    }

    private function calculatePopularity(array $counts): float
    {
        $positiveCount = $counts['positiveCount'] ?? 0;
        $negativeCount = $counts['negativeCount'] ?? 0;
        // for case - if I'm not correctly understand the task
        $totalCount = $counts['totalCount'] ?? 0;

        if ($negativeCount <= 0) {
            return 0;
        }

        // if I'm not correctly understand the task, then $negativeCount needs to be changed with $totalCount
        $score = round(($positiveCount / $negativeCount) * 10, 2);

        if ($score > 10) {
            return 10;
        }

        return $score;
    }
}