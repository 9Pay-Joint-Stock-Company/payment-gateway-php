<?php
declare(strict_types=1);

namespace NinePay\Request;

use Symfony\Component\String\UnicodeString;

/**
 * Class AbstractRequest
 *
 * Base class for requests, handling dynamic property access and payload conversion.
 */
abstract class AbstractRequest
{
    /**
     * @var array
     */
    protected array $payload = [];

    /**
     * Set a value in the payload using magic setter.
     * Converts property name to snake_case.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $convertName = (new UnicodeString($name))->snake()->toString();

        $this->payload[$convertName] = $value;
    }

    /**
     * Get a value from the payload using magic getter.
     * Converts property name to snake_case.
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        $convertName = (new UnicodeString($name))->snake()->toString();

        return $this->payload[$convertName] ?? null;
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
