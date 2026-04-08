<?php
declare(strict_types=1);

namespace NinePay\Tests\Unit\Gateways;

use NinePay\Config\NinePayConfig;
use NinePay\Gateways\NinePayGateway;
use NinePay\Request\CapturePaymentRequest;
use NinePay\Utils\HttpClient;
use PHPUnit\Framework\TestCase;

class NinePayCaptureTest extends TestCase
{
    private NinePayConfig $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new NinePayConfig('MID123', 'SECRET', 'CHECKSUM', 'https://payment.example.com');
    }

    public function testCaptureSuccess(): void
    {
        $mockHttp = $this->createMock(HttpClient::class);
        $mockHttp->expects($this->once())
            ->method('post')
            ->willReturn([
                'status' => 200,
                'body' => [
                    'status' => 200,
                    'message' => 'Capture successful',
                    'data' => [
                        'transaction_id' => 'TRANS123',
                        'order_code' => 123456,
                        'amount' => 50000
                    ]
                ]
            ]);

        $gateway = new NinePayGateway($this->config, $mockHttp);

        $request = new CapturePaymentRequest('REQ_CAP_1', 123456, 50000, 'VND');

        $response = $gateway->capture($request);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('Capture successful', $response->getMessage());
        $this->assertEquals(123456, $response->getData()['data']['order_code']);
    }

    public function testCaptureFails(): void
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
        $request = new CapturePaymentRequest('REQ_CAP_FAIL', 654321, 50000);

        $response = $gateway->capture($request);

        $this->assertFalse($response->isSuccess());
        $this->assertEquals('Invalid transaction state', $response->getMessage());
    }

    public function testInputValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields');
        new CapturePaymentRequest('', 0, 0);
    }
    
    public function testAmountValidation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount must be positive');
        new CapturePaymentRequest('REQ_INV', 123, -100);
    }

    public function testCaptureHttpError(): void
    {
        $mockHttp = $this->createMock(HttpClient::class);
        $mockHttp->expects($this->once())
            ->method('post')
            ->willReturn([
                'status' => 500,
                'body' => 'Internal Server Error' 
            ]);

        $gateway = new NinePayGateway($this->config, $mockHttp);
        $request = new CapturePaymentRequest('REQ_CAP_ERR', 777777, 50000);

        $response = $gateway->capture($request);

        $this->assertFalse($response->isSuccess());
    }
}
