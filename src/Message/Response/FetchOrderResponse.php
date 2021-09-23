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
}
