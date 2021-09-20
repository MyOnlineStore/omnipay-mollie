<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/mandates-api/list-mandates
 */
class FetchCustomerMandatesResponse extends AbstractMollieResponse
{
    /**
     * @return array<string, mixed>|null
     */
    public function getMandates(): ?array
    {
        return $this->data['_embedded']['mandates'] ?? null;
    }

    public function hasValidMandates(): bool
    {
        if ($mandates = $this->getMandates()) {
            foreach ($mandates as $mandate) {
                if ('valid' === $mandate['status']) {
                    return true;
                }
            }
        }

        return false;
    }
}
