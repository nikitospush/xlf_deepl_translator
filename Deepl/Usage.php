<?php

declare(strict_types=1);

namespace App\Services\Deepl;

final class Usage extends DeeplMethod
{
    /**
     * {@inheritdoc}
     */
    public function method(): string
    {
        return DeeplMethod::GET;
    }

    /**
     * {@inheritdoc}
     */
    public function path(): string
    {
        return 'usage';
    }

    /**
     * {@inheritdoc}
     */
    public function mapTo(): string
    {
        return UsageResult::class;
    }
}
