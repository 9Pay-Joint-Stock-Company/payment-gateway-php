<?php
declare(strict_types=1);

namespace NinePay\Config;

use NinePay\Exceptions\InvalidConfigException;

class NinePayConfig
{
    private string $merchantId;
    private string $secretKey;
    private string $checksumKey;
    private string $env;

    /**
     * @param string $merchantId
     * @param string $secretKey
     * @param string $checksumKey
     * @param string $env
     * @throws InvalidConfigException
     */
    public function __construct(string $merchantId, string $secretKey, string $checksumKey, string $env = 'SANDBOX')
    {
        if ($merchantId === '' || $secretKey === '' || $checksumKey === '') {
            throw new InvalidConfigException('NinePay config requires merchant_id, secret_key, checksum_key');
        }

        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->checksumKey = $checksumKey;
        $this->env = $env;
    }

    public static function fromArray(array $config): self
    {
        return new self(
            $config['merchant_id'] ?? '',
            $config['secret_key'] ?? '',
            $config['checksum_key'] ?? '',
            $config['env'] ?? 'SANDBOX'
        );
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getChecksumKey(): string
    {
        return $this->checksumKey;
    }

    public function getEnv(): string
    {
        return $this->env;
    }
}
