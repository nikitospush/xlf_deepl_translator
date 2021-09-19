<?php

declare(strict_types=1);

namespace App\Services\Deepl;

/**
 * @psalm-immutable
 */
final class UsageResult
{
    public int $characterCount;
    public int $characterLimit;
}
