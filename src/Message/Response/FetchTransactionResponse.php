<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * @see https://docs.mollie.com/reference/v2/payments-api/get-payment
 */
class FetchTransactionResponse extends AbstractMollieResponse implements RedirectResponseInterface
{
    public function isRedirect(): bool
    {
        return isset($this->data['_links']['checkout']['href']);
    }

    public function getRedirectUrl(): ?string
    {
        if ($this->isRedirect()) {
            return $this->data['_links']['checkout']['href'];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData(): ?array
    {
        return null;
    }

    public function isOpen(): bool
    {
        return isset($this->data['status'])
            && ('open' === $this->data['status'] || 'created' === $this->data['status']);
    }

    public function isCancelled(): bool
    {
        return isset($this->data['status']) && 'canceled' === $this->data['status'];
    }

    public function isPaid(): bool
    {
        return isset($this->data['status']) && 'paid' === $this->data['status'];
    }

    public function isAuthorized(): bool
    {
        return isset($this->data['status']) && 'authorized' === $this->data['status'];
    }

    public function isPaidOut(): bool
    {
        return isset($this->data['_links']['settlement']);
    }

    public function isExpired(): bool
    {
        return isset($this->data['status']) && 'expired' === $this->data['status'];
    }

    public function isRefunded(): bool
    {
        return isset($this->data['_links']['refunds']);
    }

    public function isPartialRefunded(): bool
    {
        return $this->isRefunded()
            && isset($this->data['amountRemaining'])
            && $this->data['amountRemaining']['value'] > 0;
    }

    public function hasChargebacks(): bool
    {
        return !empty($this->data['_links']['chargebacks']);
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['id'] ?? null;
    }

    public function getTransactionId(): ?string
    {
        return $this->data['metadata']['transactionId'] ?? null;
    }

    /**
     * @return int|string|null
     */
    public function getStatus()
    {
        return $this->data['status'] ?? null;
    }

    public function getAmount(): ?string
    {
        if (isset($this->data['amount']) && \is_array($this->data['amount'])) {
            /**
             * $this->data['amount'] = [
             *      "currency" => "EUR",
             *      "value" => "50",
             * ]
             */
            return $this->data['amount']['value'];
        }

        return null;
    }

    public function getCurrency(): ?string
    {
        if ($this->isSuccessful() && \is_array($this->data['amount'])) {
            /**
             * $this->data['amount'] = [
             *      "currency" => "EUR",
             *      "value" => "50",
             * ]
             */
            return $this->data['amount']['currency'];
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getMetadata(): ?array
    {
        return $this->data['metadata'] ?? null;
    }
}
