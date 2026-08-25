<?php

declare(strict_types=1);

namespace Carlin\TranslateDrives\Tests\Unit;

use Carlin\TranslateDrives\Exceptions\RateLimitedException;
use Carlin\TranslateDrives\Exceptions\TranslateException;
use Carlin\TranslateDrives\Providers\AbstractProvider;
use Carlin\TranslateDrives\Supports\LangCode;
use Carlin\TranslateDrives\Supports\Translate;
use Carlin\TranslateDrives\Tests\TestCase;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use RuntimeException;
use Stichoza\GoogleTranslate\Exceptions\RateLimitException as GoogleRateLimitException;
use Throwable;

class RethrowTranslateFailureTest extends TestCase
{
    public function test_guzzle_429_with_retry_after_throws_rate_limited_exception(): void
    {
        $provider = new StubProvider();
        $exception = new ClientException(
            'Too Many Requests',
            new Request('POST', 'https://example.test/translate'),
            new Response(429, ['Retry-After' => '10'], 'slow down')
        );

        try {
            $provider->exposeRethrow($exception);
            $this->fail('Expected RateLimitedException');
        } catch (RateLimitedException $rateLimited) {
            $this->assertSame(429, $rateLimited->httpStatus);
            $this->assertSame(10, $rateLimited->retryAfterSeconds);
            $this->assertSame($exception, $rateLimited->getPrevious());
        }
    }

    public function test_non_429_keeps_translate_exception_and_previous(): void
    {
        $provider = new StubProvider();
        $exception = new RuntimeException('network down', 0);

        try {
            $provider->exposeRethrow($exception);
            $this->fail('Expected TranslateException');
        } catch (TranslateException $translateException) {
            $this->assertNotInstanceOf(RateLimitedException::class, $translateException);
            $this->assertSame($exception, $translateException->getPrevious());
            $this->assertSame('network down', $translateException->getMessage());
        }
    }

    public function test_parse_retry_after_http_date(): void
    {
        $seconds = RateLimitedException::parseRetryAfterHeader(gmdate('D, d M Y H:i:s', time() + 25) . ' GMT');
        $this->assertNotNull($seconds);
        $this->assertGreaterThanOrEqual(20, $seconds);
        $this->assertLessThanOrEqual(30, $seconds);
    }

    public function test_google_rate_limit_exception_converts_with_previous(): void
    {
        $previous = new GoogleRateLimitException('blocked', 429);
        $converted = RateLimitedException::fromThrowable($previous);

        $this->assertSame(429, $converted->httpStatus);
        $this->assertSame($previous, $converted->getPrevious());
        $this->assertNull($converted->retryAfterSeconds);
    }
}

class StubProvider extends AbstractProvider
{
    public function exposeRethrow(Throwable $exception): never
    {
        $this->rethrowTranslateFailure($exception);
    }

    protected function handlerTranslate(string $query, string $to = LangCode::EN, string $from = LangCode::AUTO): Translate
    {
        throw new RuntimeException('not used');
    }

    protected function mapTranslateResult(array $translateResult): array
    {
        return $translateResult;
    }
}
