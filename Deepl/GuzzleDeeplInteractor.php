<?php

declare(strict_types=1);

namespace App\Services\Deepl;

use App\Std\Result\Failure;
use App\Std\Result\Result;
use App\Std\Result\Success;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class GuzzleDeeplInteractor implements DeeplInteractor
{
    private DenormalizerInterface $denormalizer;
    private ClientInterface $guzzle;
    private string $key;
    private string $host;

    public function __construct(
        DenormalizerInterface $denormalizer,
        ClientInterface $guzzle,
        string $key,
        string $host
    ) {
        $this->denormalizer = $denormalizer;
        $this->guzzle = $guzzle;
        $this->key = $key;
        $this->host = $host;
    }

    /**
     * @template T
     *
     * @param DeeplMethod<T> $method
     *
     * @return Success<T>|Failure<string>
     */
    public function call(DeeplMethod $method): Result
    {
        $options = [
            RequestOptions::HEADERS => [
                RequestOptions::HTTP_ERRORS => false,
            ],
        ];

        if ($method->isPost()) {
            $options[RequestOptions::HEADERS] = array_merge(
                $options[RequestOptions::HEADERS],
                ['Content-Type' => 'application/x-www-form-urlencoded']
            );

            $options[RequestOptions::FORM_PARAMS] = $method->query();
        }

        try {
            $response = $this->guzzle->request($method->method(), $this->formatUri($method), $options);

            if (200 === $response->getStatusCode()) {
                $payload = json_decode((string) $response->getBody(), true);

                if (!is_null($method->jsonRoot())) {
                    $payload = isset($payload[$method->jsonRoot()]) ? current($payload[$method->jsonRoot()]) : $payload;
                }

                /** @psalm-var T $result */
                $result = $this->denormalizer->denormalize($payload, $method->mapTo(), 'array');

                return new Success($result);
            }

            return $this->handleFailure($response);
        } catch (\Throwable $throwable) {
            if ($throwable instanceof ClientException) {
                return $this->handleFailure($throwable->getResponse());
            }

            return new Failure($throwable->getMessage());
        }
    }

    private function formatUri(DeeplMethod $method): string
    {
        $uri = trim($this->host, '/').'/'.trim($method->path(), '/');

        $query = array_merge($method->query(), ['auth_key' => $this->key]);

        return $uri.'?'.http_build_query($query);
    }

    /**
     * @return Failure<string>
     */
    private function handleFailure(
        ResponseInterface $response,
        string $alternativeMessage = 'Unrecognized error.'
    ): Failure {
        if (isset(self::ERRORS[$response->getStatusCode()])) {
            return new Failure(self::ERRORS[$response->getStatusCode()]);
        }

        return new Failure($alternativeMessage);
    }
}
