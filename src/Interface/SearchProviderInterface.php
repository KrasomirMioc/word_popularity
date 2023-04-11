<?php

namespace App\Interface;

interface SearchProviderInterface
{
    public function getSearchResult(string $term): array;
    public function getPositiveResultCount(string $term): int;
    public function getNegativeResultCount(string $term): int;
    public function getResultForTotalCount(string $term): int;
    public function getProviderName(): string;
}