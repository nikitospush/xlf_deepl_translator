<?php

declare(strict_types=1);

namespace App\Services\Deepl;

/**
 * @psalm-immutable
 */
final class TranslateResult
{
    public string $detectedSourceLanguage;
    public string $text;
}
