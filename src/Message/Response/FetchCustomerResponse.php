<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/customers-api/get-customer
 */
class FetchCustomerResponse extends AbstractMollieResponse
{
    public function getCustomerReference(): ?string
    {
        return $this->data['id'] ?? null;
    }
}
