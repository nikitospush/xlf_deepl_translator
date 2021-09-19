<?php

declare(strict_types=1);

namespace App\Services\Deepl;

use Webmozart\Assert\Assert;

final class Translate extends DeeplMethod
{
    private string $text;
    private string $lang;

    /**
     * @param string $text
     * @param string $lang
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $text, string $lang = 'DE')
    {
        Assert::stringNotEmpty($text);
        Assert::stringNotEmpty($lang);

        $this->text = $text;
        $this->lang = $lang;
    }

    /**
     * {@inheritdoc}
     */
    public function method(): string
    {
        return DeeplMethod::POST;
    }

    /**
     * @return string|null
     */
    public function jsonRoot(): ?string
    {
        return 'translations';
    }

    /**
     * {@inheritdoc}
     */
    public function path(): string
    {
        return 'translate';
    }

    /**
     * {@inheritdoc}
     */
    public function query(): array
    {
        return [
            'text'        => $this->text,
            'target_lang' => $this->lang,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function mapTo(): string
    {
        return TranslateResult::class;
    }
}
