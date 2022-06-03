<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Response\FetchOrderResponse;

/**
 * Retrieve a single order object by its payment token.
 *
 * @see https://docs.mollie.com/reference/v2/payments-api/get-order
 *
 * @method FetchOrderResponse send()
 */
class FetchOrderRequest extends AbstractMollieRequest
{
    /**
     * @return array<array-key, mixed>
     *
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('apiKey', 'transactionReference');

        $data = [];
        $data['id'] = $this->getTransactionReference();

        return $data;
    }

    public function hasIncludePayments(): bool
    {
        return (bool) $this->getParameter('includePayments');
    }

    /**
     * @return $this
     */
    public function setIncludePayments(bool $includePayments = true): static
    {
        return $this->setParameter('includePayments', $includePayments);
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public function sendData($data): FetchOrderResponse
    {
        $response = $this->sendRequest(
            self::GET,
            \sprintf(
                '/orders/%s%s',
                $data['id'],
                $this->hasIncludePayments() ? '?embed=payments' : ''
            )
        );

        return $this->response = new FetchOrderResponse($this, $response);
    }
}
