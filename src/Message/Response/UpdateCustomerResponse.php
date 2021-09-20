<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/customers-api/update-customer
 */
class UpdateCustomerResponse extends AbstractMollieResponse
{
    public function getCustomerReference(): ?string
    {
        return $this->data['id'] ?? null;
    }
}
