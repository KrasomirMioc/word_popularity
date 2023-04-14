<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Browser\Json;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class WordPopularityApiV2Test extends KernelTestCase
{
    use HasBrowser, ResetDatabase;

    protected string $baseUrlApiV2 = '/api/v2/words';

    public function testGetScoreForTerm()
    {
        $this->browser()
            ->get($this->baseUrlApiV2 . '/search?term=php')
            ->assertJson()
            ->use(function (Json $json) {
                $json->assertMatches('keys(data.attributes)', [
                    'score', 'term', 'positiveCount', 'negativeCount',
                    'totalCount', 'source', 'createdAt',
                ]);
                $json->assertHas('meta');
                $json->assertHas('jsonapi');
                $json->assertHas('jsonapi.version');
            })
            ->assertStatus(Response::HTTP_OK);
    }

    public function testReturnValidationErrorOnEmptyTerm()
    {
        $this->browser()
            ->get($this->baseUrlApiV2 . '/search')
            ->assertJson()
            ->use(function (Json $json) {
                $json->assertMatches('keys(errors[0])', [
                    'id', 'links', 'status', 'code',
                    'title', 'detail', 'source', 'meta',
                ]);
                $json->assertHas('meta');
                $json->assertHas('jsonapi');
                $json->assertHas('jsonapi.version');
            })
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}