<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/orders-api/cancel-order
 */
final class CancelOrderResponse extends FetchOrderResponse
{
    public function isSuccessful(): bool
    {
        if (!isset($this->data['status'])) {
            return false;
        }

        return 'canceled' === $this->data['status'];
    }
}
