<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Browser\Json;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class WordPopularityApiV1Test extends KernelTestCase
{
    use HasBrowser, ResetDatabase;

    protected string $baseUrlApiV1 = '/api/v1/words';

    public function testGithubTokenExist()
    {
        $this->browser()
            ->get($this->baseUrlApiV1 . '/search?term=php')
            ->assertJson()
            ->assertStatus(Response::HTTP_OK);
    }

    public function testGetScoreForTerm()
    {
        $this->browser()
            ->get($this->baseUrlApiV1 . '/search?term=php')
            ->assertJson()
            ->assertJsonMatches('term', 'php')
            ->use(function (Json $json) {
                $json->assertHas('score');
            })
            ->assertStatus(Response::HTTP_OK);
    }

    public function testReturnValidationErrorOnEmptyTerm()
    {
        $this->browser()
            ->get($this->baseUrlApiV1 . '/search?term=')
            ->assertJson()
            ->use(function (Json $json) {
                $json->assertHas('message');
            })
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}