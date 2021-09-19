<?php

declare(strict_types=1);

namespace App\Services\Deepl;

use App\Std\Result\Failure;
use App\Std\Result\Result;
use App\Std\Result\Success;

interface DeeplInteractor
{
    public const ERRORS = [
        400 => 'Bad request. Please check error message and your parameters.',
        403 => 'Authorization failed. Please supply a valid auth_key parameter.',
        404 => 'The requested resource could not be found.',
        413 => 'The request size exceeds the limit.',
        414 => 'The request URL is too long. You can avoid this error by using a POST request instead of a GET request.',
        429 => 'Too many requests. Please wait and resend your request.',
        456 => 'Quota exceeded. The character limit has been reached.',
        503 => 'Resource currently unavailable. Try again later.',
        500 => 'Internal error',
    ];

    /**
     * @template T
     *
     * @param DeeplMethod<T> $method
     *
     * @return Success<T>|Failure<string>
     */
    public function call(DeeplMethod $method): Result;
}
