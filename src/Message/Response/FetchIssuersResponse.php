<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

use Omnipay\Common\Issuer;
use Omnipay\Common\Message\FetchIssuersResponseInterface;

/**
 * @see https://docs.mollie.com/reference/v2/methods-api/get-method
 */
class FetchIssuersResponse extends AbstractMollieResponse implements FetchIssuersResponseInterface
{
    /**
     * Return available issuers as an associative array.
     *
     * @return Issuer[]
     */
    public function getIssuers(): array
    {
        if (false === isset($this->data['issuers'])) {
            return [];
        }

        $issuers = [];
        foreach ($this->data['issuers'] as $issuer) {
            $issuers[] = new Issuer($issuer['id'], $issuer['name'], $this->data['id']);
        }

        return $issuers;
    }
}
