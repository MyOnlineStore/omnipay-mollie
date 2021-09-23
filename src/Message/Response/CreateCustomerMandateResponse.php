<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/mandates-api/create-mandate
 */
class CreateCustomerMandateResponse extends AbstractMollieResponse
{
    public function getMandateId(): ?string
    {
        return $this->data['id'] ?? null;
    }

    public function isSuccessful(): bool
    {
        return isset($this->data['id']);
    }
}
