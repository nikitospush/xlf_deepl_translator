<?php

declare(strict_types=1);

namespace App\Services\Deepl;

/**
 * @template T of object
 */
abstract class DeeplMethod
{
    public const GET = 'GET';
    public const POST = 'POST';

    /**
     * @return string
     */
    abstract public function path(): string;

    /**
     * @return array<string, string|int>
     */
    public function query(): array
    {
        return [];
    }

    /**
     * @return string
     */
    abstract public function method(): string;

    /**
     * @return class-string<T>
     */
    abstract public function mapTo(): string;

    /**
     * @return string|null
     */
    public function jsonRoot(): ?string
    {
        return null;
    }

    final public function isGet(): bool
    {
        return self::GET === $this->method();
    }

    /**
     * @return bool
     */
    final public function isPost(): bool
    {
        return self::POST === $this->method();
    }
}
