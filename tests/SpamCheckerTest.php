<?php

namespace App\Tests;

use App\Entity\Comment;
use App\SpamChecker;
use http\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpamCheckerTest extends TestCase
{
    public function testSpamScoreWithInvalidRequest(): void
    {

        $comment = new Comment();
        $comment->setCreatedAt();
        $context = [];

        $client = new MockHttpClient([new MockResponse('invalid', ['response_headers' => ['x-akismet-debug-help: Invalid key']])]);
        $checker =  new SpamChecker($client, 'abcde');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to check for spam: invalid (Invalid key)');
        $checker->getSpamScore($comment, $context);
    }

    /**
     * @param int $expectedScore
     * @param ResponseInterface $response
     * @param Comment $comment
     * @param array $context
     * @dataProvider provideComments
     */
    public function testSpamScore(int $expectedScore, ResponseInterface $response, Comment $comment, array $context)
    {
        $client = new MockHttpClient([$response]);

        $checker = new SpamChecker($client, 'abcde');

        $score = $checker->getSpamScore($comment, $context);
        static::assertSame($expectedScore, $score);
    }

    public static function provideComments(): iterable
    {
        $comment =  new Comment();
        $comment->setCreatedAt();
        $context = [];

        $response =  new MockResponse('', ['response_headers' => ['x-akismet-pro-tip: discard']]);
        yield 'blatant_spam' =>  [2,$response,$comment,$context];

        $response = new MockResponse('true');
        yield 'spam' => [1, $response, $comment, $context];

        $response = new MockResponse('false');
        yield 'ham' => [0, $response, $comment, $context];
    }
}
