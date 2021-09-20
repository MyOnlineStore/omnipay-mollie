<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/payments-api/get-payment
 */
class CompleteOrderResponse extends FetchOrderResponse
{
    public function isSuccessful(): bool
    {
        return parent::isSuccessful() && $this->isPaid();
    }

    /**
     * The order status is never a redirect
     *
     * {@inheritdoc}
     */
    public function isRedirect(): bool
    {
        return false;
    }
}
