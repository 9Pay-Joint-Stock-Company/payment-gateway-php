<?php
declare(strict_types=1);

namespace NinePay\Tests\Unit\Gateways;

use NinePay\Config\NinePayConfig;
use NinePay\Gateways\NinePayGateway;
use NinePay\Request\ReverseCardPaymentRequest;
use NinePay\Utils\HttpClient;
use PHPUnit\Framework\TestCase;

class NinePayReverseAuthTest extends TestCase
{
    private NinePayConfig $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new NinePayConfig('MID123', 'SECRET', 'CHECKSUM', 'https://payment.example.com');
    }

    public function testReverseAuthSuccess(): void
    {
        $mockHttp = $this->createMock(HttpClient::class);
        $mockHttp->expects($this->once())
            ->method('post')
            ->willReturn([
                'status' => 200,
                'body' => [
                    'status' => 200,
                    'message' => 'Reverse successful',
                    'data' => [
                        'transaction_id' => 'TRANS_REV_123',
                    ]
                ]
            ]);

        $gateway = new NinePayGateway($this->config, $mockHttp);

        $request = new ReverseCardPaymentRequest('REQ_REV_1', 123456);

        $response = $gateway->reverseCardPayment($request);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('Reverse successful', $response->getMessage());
        $this->assertEquals('TRANS_REV_123', $response->getData()['data']['transaction_id']);
    }

    public function testReverseAuthFailure(): void
    {
        $mockHttp = $this->createMock(HttpClient::class);
        $mockHttp->expects($this->once())
            ->method('post')
            ->willReturn([
                'status' => 400,
                'body' => [
                    'status' => 400,
                    'message' => 'Invalid transaction state'
                ]
            ]);

        $gateway = new NinePayGateway($this->config, $mockHttp);
        $request = new ReverseCardPaymentRequest('REQ_REV_FAIL', 654321);

        $response = $gateway->reverseCardPayment($request);

        $this->assertFalse($response->isSuccess());
        $this->assertEquals('Invalid transaction state', $response->getMessage());
    }

    public function testReverseAuthHttpError(): void
    {
        $mockHttp = $this->createMock(HttpClient::class);
        $mockHttp->expects($this->once())
            ->method('post')
            ->willReturn([
                'status' => 500,
                'body' => 'Internal Server Error' 
            ]);

        $gateway = new NinePayGateway($this->config, $mockHttp);
        $request = new ReverseCardPaymentRequest('REQ_REV_ERR', 777777);

        $response = $gateway->reverseCardPayment($request);

        $this->assertFalse($response->isSuccess());
    }

    public function testReverseAuthException(): void
    {
        $mockHttp = $this->createMock(HttpClient::class);
        $mockHttp->method('post')->willThrowException(new \Exception('Connection timeout'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Connection timeout');

        $gateway = new NinePayGateway($this->config, $mockHttp);
        $request = new ReverseCardPaymentRequest('REQ_REV_EX', 888888);

        $gateway->reverseCardPayment($request);
    }

    public function testInputValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields');
        new ReverseCardPaymentRequest('', 0);
    }
}
