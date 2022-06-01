<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Response\CompleteOrderResponse;

/**
 * Retrieve a single order object by its payment token.
 *
 * @see https://docs.mollie.com/reference/v2/payments-api/get-order
 *
 * @method CompleteOrderResponse send()
 */
class CompleteOrderRequest extends FetchOrderRequest
{
    /**
     * @return array<array-key, mixed>
     *
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('apiKey');

        $data = [];
        $data['id'] = $this->getTransactionReference();

        if (!isset($data['id'])) {
            $data['id'] = $this->httpRequest->request->get('id');
        }

        if (empty($data['id'])) {
            throw new InvalidRequestException('The transactionReference parameter is required');
        }

        return $data;
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public function sendData($data): CompleteOrderResponse
    {
        $response = $this->sendRequest(self::GET, '/orders/' . $data['id']);

        return $this->response = new CompleteOrderResponse($this, $response);
    }
}
