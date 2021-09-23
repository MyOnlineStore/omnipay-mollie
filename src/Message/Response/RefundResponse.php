<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/refunds-api/create-refund
 */
class RefundResponse extends AbstractMollieResponse
{
    public function getTransactionReference(): ?string
    {
        return $this->data['paymentId'];
    }

    public function getTransactionId(): string
    {
        return $this->data['id'];
    }

    public function isSuccessful(): bool
    {
        return isset($this->data['id']);
    }
}
