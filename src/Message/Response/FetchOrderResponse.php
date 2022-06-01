<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

use Omnipay\Common\ItemBag;
use Omnipay\Mollie\Item;

/**
 * @see https://docs.mollie.com/reference/v2/payments-api/get-order
 */
class FetchOrderResponse extends FetchTransactionResponse
{
    /**
     * @return array<string, mixed>|null
     */
    public function getLines(): ?array
    {
        return $this->data['lines'] ?? null;
    }

    public function getItems(): ?ItemBag
    {
        if (isset($this->data['lines'])) {
            $items = [];

            foreach ($this->data['lines'] as $line) {
                $items[] = new Item($line);
            }

            return new ItemBag($items);
        }

        return null;
    }

    /**
     * Get the payments provided by enabling FetchOrderRequest::setIncludePayments()
     *
     * @return list<FetchTransactionResponse>
     */
    public function getPayments(): array
    {
        if (
            !isset($this->data['_embedded']['payments'])
            || !\is_array($this->data['_embedded']['payments'])
        ) {
            return [];
        }

        return \array_map(
            fn (array $payment): FetchTransactionResponse => new FetchTransactionResponse($this->request, $payment),
            $this->data['_embedded']['payments']
        );
    }
}
