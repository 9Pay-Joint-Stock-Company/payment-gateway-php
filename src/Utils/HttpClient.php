<?php
declare(strict_types=1);

namespace NinePay\Utils;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class HttpClient
 * 
 * Wrapper for GuzzleHttp to send API requests.
 */
class HttpClient
{
    /** @var GuzzleClient */
    private GuzzleClient $client;

    /**
     * HttpClient constructor.
     *
     * @param GuzzleClient|null $client
     */
    public function __construct(?GuzzleClient $client = null)
    {
        $this->client = $client ?? new GuzzleClient([
            'http_errors' => false,
            'timeout' => 15,
        ]);
    }

    /**
     * Send a GET request.
     *
     * @param string $url
     * @param array $headers
     * @return array{status:int,body:mixed,headers:array}
     */
    public function get(string $url, array $headers = []): array
    {
        try {
            $res = $this->client->request('GET', $url, [
                'headers' => $headers,
            ]);

            return $this->normalizeResponse($res->getStatusCode(), (string)$res->getBody(), $res->getHeaders());
        } catch (GuzzleException $e) {
            return $this->handleErrorResponse($e);
        }
    }

    /**
     * Send a POST request.
     *
     * @param string $url
     * @param array|string|null $jsonOrBody
     * @param array $headers
     * @return array{status:int,body:mixed,headers:array}
     */
    public function post(string $url, $jsonOrBody = null, array $headers = []): array
    {
        $options = ['headers' => $headers];
        if (is_array($jsonOrBody)) {
            $options['json'] = $jsonOrBody;
        } elseif (is_string($jsonOrBody)) {
            $options['body'] = $jsonOrBody;
        }
        try {
            $res = $this->client->request('POST', $url, $options);

            return $this->normalizeResponse($res->getStatusCode(), (string)$res->getBody(), $res->getHeaders());
        } catch (GuzzleException $e) {
            return $this->handleErrorResponse($e);
        }
    }

    /**
     * @param int $status
     * @param array|string $body
     * @param array $rawHeaders
     * @return array{status:int,body:mixed,headers:array}
     */
    private function normalizeResponse(int $status, $body, array $rawHeaders = []): array
    {
        $data = $body;
        if (!is_array($body)) {
            $data = json_decode($body, true) ?: [];
        }

        return [
            'status' => $status,
            'body' => $data,
            'headers' => $rawHeaders,
        ];
    }

    private function handleErrorResponse(GuzzleException $exception): array
    {
        if ($exception instanceof RequestException && $exception->getResponse()) {
            return $this->normalizeResponse(
                $exception->getResponse()->getStatusCode() ?? 500,
                $exception->getResponse()->getBody()->getContents(),
                $exception->getResponse()->getHeaders()
            );
        }

        return $this->normalizeResponse(
            500,
            ['error' => $exception->getMessage()]
        );
    }
}
