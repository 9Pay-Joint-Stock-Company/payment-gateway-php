<?php
declare(strict_types=1);

namespace NinePay\Tests\Unit\Gateways;

use NinePay\Config\NinePayConfig;
use NinePay\Gateways\NinePayGateway;
use NinePay\Support\CreatePaymentRequest;
use NinePay\Utils\HttpClient;
use PHPUnit\Framework\TestCase;

class NinePayGatewayTest extends TestCase
{
    private NinePayConfig $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new NinePayConfig('MID123', 'SECRET', 'CHECKSUM', 'SANDBOX');
    }

    public function testCreatePaymentReturnsRedirectUrl(): void
    {
        $gateway = new NinePayGateway($this->config);
        $request = new CreatePaymentRequest('REQ123', '10000', 'Test');
        
        $response = $gateway->createPayment($request);
        
        $this->assertTrue($response->isSuccess());
        $data = $response->getData();
        $this->assertArrayHasKey('redirect_url', $data);
        $this->assertStringContainsString('sand-payment.9pay.vn/portal', $data['redirect_url']);
        $this->assertStringContainsString('sand-payment.9pay.vn/portal', $data['redirect_url']);
        $this->assertStringContainsString('signature=', $data['redirect_url']);
    }

    public function testCreatePaymentWithOptionalParameters(): void
    {
        $gateway = new NinePayGateway($this->config);
        $request = (new CreatePaymentRequest('REQ123', '10000', 'Test'))
            ->withMethod(\NinePay\Enums\PaymentMethod::CREDIT_CARD)
            ->withClientIp('127.0.0.1')
            ->withCurrency(\NinePay\Enums\Currency::VND)
            ->withLang(\NinePay\Enums\Language::VI)
            ->withCardToken('TOKEN123')
            ->withSaveToken(1)
            ->withTransactionType(\NinePay\Enums\TransactionType::INSTALLMENT)
            ->withClientPhone('0901234567')
            ->withExpiresTime(100);

        $response = $gateway->createPayment($request);

        $this->assertTrue($response->isSuccess());
        $data = $response->getData();
        $query = parse_url($data['redirect_url'], PHP_URL_QUERY);
        parse_str($query, $params);
        
        $decodedPayload = json_decode(base64_decode($params['baseEncode']), true);
        
        $this->assertEquals(\NinePay\Enums\PaymentMethod::CREDIT_CARD, $decodedPayload['method']);
        $this->assertEquals('127.0.0.1', $decodedPayload['client_ip']);
        $this->assertEquals(\NinePay\Enums\Currency::VND, $decodedPayload['currency']);
        $this->assertEquals(\NinePay\Enums\Language::VI, $decodedPayload['lang']);
        $this->assertEquals('TOKEN123', $decodedPayload['card_token']);
        $this->assertEquals(1, $decodedPayload['save_token']);
        $this->assertEquals(\NinePay\Enums\TransactionType::INSTALLMENT, $decodedPayload['transaction_type']);
        $this->assertEquals('0901234567', $decodedPayload['client_phone']);
        $this->assertEquals(100, $decodedPayload['expires_time']);
    }

    public function testVerifyReturnsTrueForValidPayload(): void
    {
        $gateway = new NinePayGateway($this->config);
        $result = 'some-result';
        $checksum = strtoupper(hash('sha256', $result . $this->config->getChecksumKey()));

        $this->assertTrue($gateway->verify($result, $checksum));
    }

    public function testVerifyReturnsFalseForInvalidPayload(): void
    {
        $gateway = new NinePayGateway($this->config);
        $result = 'some-result';
        $checksum = 'wrong-checksum';

        $this->assertFalse($gateway->verify($result, $checksum));
    }

    public function testDecodeResult(): void
    {
        $gateway = new NinePayGateway($this->config);
        // "test" encoded in urlsafe base64 is "dGVzdA"
        $this->assertEquals('test', $gateway->decodeResult('dGVzdA'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testInquiry(): void
    {
        $mockHttp = $this->createMock(HttpClient::class);
        $mockHttp->method('get')->willReturn([
            'status' => 200,
            'body' => ['message' => 'Success', 'status' => 'success'],
            'headers' => []
        ]);

        $gateway = new NinePayGateway($this->config, $mockHttp);

         $result = $gateway->inquiry('TRANS123');

         $this->assertTrue($result->isSuccess());
    }
}
