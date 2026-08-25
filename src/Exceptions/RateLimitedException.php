<?php

namespace Carlin\TranslateDrives\Exceptions;

use GuzzleHttp\Exception\RequestException;
use Throwable;

class RateLimitedException extends TranslateException
{
    public readonly int $httpStatus;

    public readonly ?int $retryAfterSeconds;

    public function __construct(
        string $message = '',
        int $httpStatus = 429,
        ?Throwable $previous = null,
        ?int $retryAfterSeconds = null
    ) {
        $this->httpStatus = $httpStatus;
        $this->retryAfterSeconds = $retryAfterSeconds;
        parent::__construct($message, $httpStatus, $previous);
    }

    public static function fromThrowable(Throwable $exception): self
    {
        $httpStatus = 429;
        $retryAfterSeconds = null;

        if ($exception instanceof RequestException && $exception->hasResponse()) {
            $response = $exception->getResponse();
            $httpStatus = $response->getStatusCode();
            $retryAfterSeconds = self::parseRetryAfterHeader($response->getHeaderLine('Retry-After'));
        } elseif (is_int($exception->getCode()) && $exception->getCode() > 0) {
            $httpStatus = $exception->getCode();
        }

        return new self($exception->getMessage(), $httpStatus, $exception, $retryAfterSeconds);
    }

    public static function parseRetryAfterHeader(string $header): ?int
    {
        $header = trim($header);
        if ($header === '') {
            return null;
        }

        if (ctype_digit($header)) {
            return (int) $header;
        }

        $timestamp = strtotime($header);
        if ($timestamp === false) {
            return null;
        }

        return max(0, $timestamp - time());
    }
}
