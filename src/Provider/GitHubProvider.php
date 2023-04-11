<?php

namespace App\Provider;

use App\Interface\SearchProviderInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubProvider implements SearchProviderInterface
{
    private string $providerName = 'github';
    protected const GITHUB_URL = 'https://api.github.com/search/issues';
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient, string $githubToken)
    {
        $this->httpClient = $httpClient->withOptions([
            'headers' => [
                'Accept' => 'application/vnd.github+json',
                'Authorization' => 'Bearer ' . $githubToken
            ]
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getSearchResult(string $term): array
    {
        return [
            'positiveCount' => $this->getPositiveResultCount($term),
            'negativeCount' => $this->getNegativeResultCount($term),
            'totalCount' => $this->getResultForTotalCount($term),
        ];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getPositiveResultCount(string $term): int
    {
        $queryString = http_build_query([
            "q" => "$term+rocks"
        ]);

        return $this->getResult($this->parseUrl($queryString));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getNegativeResultCount(string $term): int
    {
        $queryString = http_build_query([
            "q" => "$term+sucks"
        ]);

        return $this->getResult($this->parseUrl($queryString));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getResultForTotalCount(string $term): int
    {
        $queryString = http_build_query([
            "q" => "$term"
        ]);

        return $this->getResult($this->parseUrl($queryString));
    }

    private function parseUrl(string $queryString): string
    {
        return implode('?', [
            self::GITHUB_URL,
            $queryString
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getResult(string $url): int
    {
        $response = $this->httpClient->request('GET', $url);
        $parsedResponse = $response->toArray();

        return $parsedResponse['total_count'];
    }

    public function getProviderName(): string
    {
        return $this->providerName;
    }
}