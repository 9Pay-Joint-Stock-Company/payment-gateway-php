<?php
declare(strict_types=1);

namespace NinePay\Tests\Unit\Gateways;

use NinePay\Config\NinePayConfig;
use NinePay\Gateways\NinePayGateway;
use NinePay\Request\PayerAuthRequest;
use PHPUnit\Framework\TestCase;
use NinePay\Utils\HttpClient;

class NinePayGatewayPayerAuthTest extends TestCase
{
    private $config;
    private $http;
    private $gateway;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new NinePayConfig('merchant', 'secret', 'checksum');
        $this->http = $this->createMock(HttpClient::class);
        $this->gateway = new NinePayGateway($this->config, $this->http);
    }

    public function test_payer_auth_success()
    {
        $request = new PayerAuthRequest(
            'req_123',
            5000000,
            'https://callback.url'
        );
        $request->withInstallment(5000000, 'VCB', 12)
            ->withCard('123', 'NGUYEN VAN A', 12, 25, '123');

        $this->http->expects($this->once())
            ->method('post')
            ->with(
                $this->stringContains('/v2/payments/payer-auth'),
                $this->callback(function ($payload) {
                    return $payload['request_id'] === 'req_123' &&
                           $payload['amount'] == 5000000;
                }),
                $this->callback(function ($headers) {
                    return isset($headers['Authorization']) &&
                           strpos($headers['Authorization'], 'Signature Algorithm=HS256') !== false;
                })
            )
            ->willReturn([
                'status' => 200,
                'body' => ['status' => 'success', 'message' => 'Auth successful']
            ]);

        $response = $this->gateway->payerAuth($request);
        $this->assertTrue($response->isSuccess());
        $this->assertEquals('Auth successful', $response->getMessage());
    }

    public function test_payer_auth_failure()
    {
        $request = new PayerAuthRequest(
            'req_123',
            5000000,
            'https://callback.url'
        );
        $request->withInstallment(5000000, 'VCB', 12)
            ->withCard('123', 'NGUYEN VAN A', 12, 25, '123');

        $this->http->expects($this->once())
            ->method('post')
            ->willReturn([
                'status' => 400,
                'body' => ['message' => 'Invalid card']
            ]);

        $response = $this->gateway->payerAuth($request);
        $this->assertFalse($response->isSuccess());
        $this->assertEquals('Invalid card', $response->getMessage());
    }

    public function test_payer_auth_http_error()
    {
        $request = new PayerAuthRequest(
            'req_error',
            5000000,
            'https://callback.url'
        );

        $this->http->expects($this->once())
            ->method('post')
            ->willReturn([
                'status' => 500,
                'body' => 'Internal Server Error'
            ]);

        $response = $this->gateway->payerAuth($request);
        $this->assertFalse($response->isSuccess());
    }

    public function test_payer_auth_exception()
    {
        $request = new PayerAuthRequest(
            'req_exception',
            5000000,
            'https://callback.url'
        );

        $this->http->method('post')->willThrowException(new \Exception('Connection timeout'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Connection timeout');

        $this->gateway->payerAuth($request);
    }

    public function test_payer_auth_invalid_response()
    {
        $request = new PayerAuthRequest(
            'req_invalid',
            5000000,
            'https://callback.url'
        );

        $this->http->method('post')->willReturn([
            'status' => 200,
            'body' => [] // Empty body
        ]);

        $response = $this->gateway->payerAuth($request);
        $this->assertTrue($response->isSuccess());
    }
}
