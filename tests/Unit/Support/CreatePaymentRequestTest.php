<?php
declare(strict_types=1);

namespace NinePay\Tests\Unit\Support;

use NinePay\Support\CreatePaymentRequest;
use NinePay\Enums\Currency;
use NinePay\Enums\Language;
use NinePay\Enums\PaymentMethod;
use NinePay\Enums\TransactionType;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CreatePaymentRequestTest extends TestCase
{
    private CreatePaymentRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new CreatePaymentRequest('REQ123', '10000', 'Test');
    }

    public function testConstructorThrowsExceptionOnMissingFields(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields');
        new CreatePaymentRequest('', '', '');
    }

    public function testWithMethodValidatesInput(): void
    {
        $this->request->withMethod(PaymentMethod::ATM_CARD);
        $this->assertEquals(PaymentMethod::ATM_CARD, $this->request->getMethod());

        $this->expectException(InvalidArgumentException::class);
        $this->request->withMethod('INVALID_METHOD');
    }

    public function testWithCurrencyValidatesInput(): void
    {
        $this->request->withCurrency(Currency::VND);
        $this->assertEquals(Currency::VND, $this->request->getCurrency());

        $this->expectException(InvalidArgumentException::class);
        $this->request->withCurrency('INVALID_CURRENCY');
    }

    public function testWithLangValidatesInput(): void
    {
        $this->request->withLang(Language::VI);
        $this->assertEquals(Language::VI, $this->request->getLang());

        $this->expectException(InvalidArgumentException::class);
        $this->request->withLang('fr');
    }

    public function testWithTransactionTypeValidatesInput(): void
    {
        $this->request->withTransactionType(TransactionType::INSTALLMENT);
        $this->assertEquals(TransactionType::INSTALLMENT, $this->request->getTransactionType());

        $this->expectException(InvalidArgumentException::class);
        $this->request->withTransactionType('INVALID_TYPE');
    }

    public function testWithExpiresTimeValidatesRange(): void
    {
        $this->request->withExpiresTime(0);
        $this->assertEquals(0, $this->request->getExpiresTime());
        
        $this->request->withExpiresTime(100000);
        $this->assertEquals(100000, $this->request->getExpiresTime());

        $this->expectException(InvalidArgumentException::class);
        $this->request->withExpiresTime(-1);
    }
    
    public function testWithParamStoresExtraData(): void
    {
        $this->request->withParam('foo', 'bar');
        $this->request->withParam('baz', 123);
        
        $extraData = $this->request->getExtraData();
        $this->assertArrayHasKey('foo', $extraData);
        $this->assertEquals('bar', $extraData['foo']);
        $this->assertEquals(123, $extraData['baz']);
    }
}
