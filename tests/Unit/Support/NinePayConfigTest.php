<?php
declare(strict_types=1);

namespace NinePay\Tests\Unit\Support;

use NinePay\Config\NinePayConfig;
use NinePay\Exceptions\InvalidConfigException;
use PHPUnit\Framework\TestCase;

class NinePayConfigTest extends TestCase
{
    public function testConstructorValidatesInput(): void
    {
        $this->expectException(InvalidConfigException::class);
        new NinePayConfig('', '', '');
    }

    public function testFromArrayCreatesConfigCorrectly(): void
    {
        $config = NinePayConfig::fromArray([
            'merchant_id' => 'MID_ARR',
            'secret_key' => 'SEC_ARR',
            'checksum_key' => 'CHK_ARR',
        ]);

        $this->assertEquals('MID_ARR', $config->getMerchantId());
        $this->assertEquals('SEC_ARR', $config->getSecretKey());
        $this->assertEquals('CHK_ARR', $config->getChecksumKey());
        $this->assertEquals('SANDBOX', $config->getEnv()); // Default
    }

    public function testGettersReturnCorrectValues(): void
    {
        $config = new NinePayConfig('MID', 'SECRET', 'CHECKSUM', 'PROD');
        
        $this->assertEquals('MID', $config->getMerchantId());
        $this->assertEquals('SECRET', $config->getSecretKey());
        $this->assertEquals('CHECKSUM', $config->getChecksumKey());
        $this->assertEquals('PROD', $config->getEnv());
    }
}
