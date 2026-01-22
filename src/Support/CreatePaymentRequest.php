<?php
declare(strict_types=1);

namespace NinePay\Support;

use NinePay\Support\Concerns\HasPaymentAttributes;
use Symfony\Component\String\UnicodeString;

/**
 * Class CreatePaymentRequest
 *
 * Object containing payment creation request data.
 * @property string requestCode
 * @property float amount
 * @property string description
 * @property string|null backUrl
 * @property string|null returnUrl
 */
class CreatePaymentRequest
{
    use HasPaymentAttributes;

    protected array $payload = [];

    /**
     * CreatePaymentRequest constructor.
     *
     * @param string $requestCode
     * @param float $amount
     * @param string $description
     * @param string|null $backUrl
     * @param string|null $returnUrl
     */
    public function __construct(
        string $requestCode,
        float $amount,
        string $description,
        ?string $backUrl = null,
        ?string $returnUrl = null
    ) {
        if ($requestCode === '' || empty($amount) || $description === '') {
            throw new \InvalidArgumentException('Missing required fields: request_code, amount, description');
        }

        $this->requestCode = $requestCode;
        $this->amount = $amount;
        $this->description = $description;
        $this->backUrl = $backUrl;
        $this->returnUrl = $returnUrl;
    }

    public function __set(string $name, $value): void
    {
        $covertName = (new UnicodeString($name))->snake()->toString();

        $this->payload[$covertName] = $value;
    }

    public function __get(string $name)
    {
        $covertName = (new UnicodeString($name))->snake()->toString();

        return $this->payload[$covertName] ?? null;
    }

    /**
     * Convert request parameters to array payload for API.
     *
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return array_filter($this->payload);
    }
}
