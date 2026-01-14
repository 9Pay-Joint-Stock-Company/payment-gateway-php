<?php
declare(strict_types=1);

namespace NinePay\Tests\Unit\Support;

use NinePay\Support\CreatePaymentRequest;
use PHPUnit\Framework\TestCase;

class CreatePaymentRequestTest extends TestCase
{
    public function testToArray(): void
    {
        $request = new CreatePaymentRequest('REQ123', '10000', 'Test payment');

        $this->assertEquals('10000', $request->getAmount());
        $this->assertEquals('REQ123', $request->getRequestCode());
        $this->assertEquals('Test payment', $request->getDescription());
    }

    public function testToArrayWithOptionalFields(): void
    {
        $request = new CreatePaymentRequest(
            'REQ123', 
            '10000', 
            'Test payment',
            'https://example.com/back',
            'https://example.com/return'
        );

        $this->assertEquals('10000', $request->getAmount());
        $this->assertEquals('REQ123', $request->getRequestCode());
        $this->assertEquals('Test payment', $request->getDescription());
        $this->assertEquals('https://example.com/back', $request->getBackUrl());
        $this->assertEquals('https://example.com/return', $request->getReturnUrl());
    }
}
