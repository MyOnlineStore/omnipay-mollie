<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/payments-api/get-payment
 */
class CompletePurchaseResponse extends FetchTransactionResponse
{
    public function isSuccessful(): bool
    {
        return parent::isSuccessful() && $this->isPaid();
    }
}
