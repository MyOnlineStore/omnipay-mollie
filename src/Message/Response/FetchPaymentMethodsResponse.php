<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

use Omnipay\Common\Message\FetchPaymentMethodsResponseInterface;
use Omnipay\Common\PaymentMethod;

/**
 * @see https://docs.mollie.com/reference/v2/methods-api/list-methods
 */
class FetchPaymentMethodsResponse extends AbstractMollieResponse implements FetchPaymentMethodsResponseInterface
{
    /**
     * Return available payment methods as an associative array.
     *
     * @return PaymentMethod[]
     */
    public function getPaymentMethods(): array
    {
        if (false === isset($this->data['_embedded']['methods'])) {
            return [];
        }

        $paymentMethods = [];
        foreach ($this->data['_embedded']['methods'] as $method) {
            $paymentMethods[] = new PaymentMethod($method['id'], $method['description']);
        }

        return $paymentMethods;
    }
}
