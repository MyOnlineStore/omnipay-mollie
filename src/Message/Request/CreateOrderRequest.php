<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ItemBag;
use Omnipay\Mollie\Message\Response\CreateOrderResponse;

/**
 * Create an order with the Mollie API.
 *
 * @see https://docs.mollie.com/reference/v2/orders-api/create-order
 *
 * @method CreateOrderResponse send()
 */
final class CreateOrderRequest extends AbstractMollieRequest
{
    /**
     * Lines can contain a negative amount (discounts etc.)
     *
     * @var bool
     */
    protected $negativeAmountAllowed = true;

    /**
     * @return array<array-key, mixed>|null
     */
    public function getMetadata(): ?array
    {
        return $this->getParameter('metadata');
    }

    /**
     * @param array<array-key, mixed> $value
     *
     * @return $this
     */
    public function setMetadata(array $value): static
    {
        return $this->setParameter('metadata', $value);
    }

    public function getLocale(): ?string
    {
        return $this->getParameter('locale');
    }

    /**
     * @return $this
     */
    public function setLocale(string $value): static
    {
        return $this->setParameter('locale', $value);
    }

    public function getOrderNumber(): ?string
    {
        return $this->getParameter('orderNumber');
    }

    /**
     * @return $this
     */
    public function setOrderNumber(string $value): static
    {
        return $this->setParameter('orderNumber', $value);
    }

    public function getGatewayReference(): ?string
    {
        return $this->getParameter('gatewayReference');
    }

    /**
     * @return $this
     */
    public function setGatewayReference(string $value): static
    {
        return $this->setParameter('gatewayReference', $value);
    }

    public function getBillingEmail(): ?string
    {
        return $this->getParameter('billingEmail');
    }

    /**
     * @return $this
     */
    public function setBillingEmail(string $value): static
    {
        return $this->setParameter('billingEmail', $value);
    }

    public function getCustomerReference(): ?string
    {
        return $this->getParameter('customerReference');
    }

    /**
     * @return $this
     */
    public function setCustomerReference(string $value): static
    {
        return $this->setParameter('customerReference', $value);
    }

    public function getSequenceType(): ?string
    {
        return $this->getParameter('sequenceType');
    }

    /**
     * @return $this
     */
    public function setSequenceType(string $value): static
    {
        return $this->setParameter('sequenceType', $value);
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

    public function hasEnablePaymentWebhook(): bool
    {
        return (bool) $this->getParameter('enablePaymentWebhook');
    }

    /**
     * Enable webhook callbacks for payment changes. By default, your webhook is
     * only called for order changes. Note that a payment (transaction) ID will
     * be provided instead of an order ID for payment callbacks.
     *
     * @return $this
     */
    public function setEnablePaymentWebhook(bool $enablePaymentWebhook = true): static
    {
        return $this->setParameter('enablePaymentWebhook', $enablePaymentWebhook);
    }

    /**
     * Alias for lines
     *
     * @param array<array-key, mixed> $items
     *
     * @return $this
     */
    public function setLines(array $items): static
    {
        return $this->setItems($items);
    }

    /**
     * @return array<string, mixed>
     *
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
//        $this->validate('apiKey', 'amount', 'locale', 'card', 'items', 'currency', 'orderNumber', 'returnUrl');

        $data = [];
        $data['amount'] = [
            'value' => $this->getAmount(),
            'currency' => $this->getCurrency(),
        ];

        if ($card = $this->getCard()) {
            $data += $this->getCardData($card);
        }

        $data['metadata'] = $this->getMetadata();
        if ($this->getTransactionId()) {
            $data['metadata']['transactionId'] = $this->getTransactionId();
        }

        if ($card && $birthday = $card->getBirthday()) {
            $data['consumerDateOfBirth'] = $birthday;
        }

        $data['locale'] = $this->getLocale();
        $data['orderNumber'] = (string) $this->getOrderNumber();
        $data['redirectUrl'] = $this->getReturnUrl();
        $data['webhookUrl'] = $this->getNotifyUrl();
        $data['method'] = $this->getPaymentMethod();

        $data['lines'] = [];
        if ($items = $this->getItems()) {
            $data['lines'] = $this->getLines($items);
        }

        $data['payment'] = [];
        if ($issuer = $this->getIssuer()) {
            $data['payment']['issuer'] = $issuer;
        }

        if ($customerReference = $this->getCustomerReference()) {
            $data['payment']['customerId'] = $customerReference;
        }

        if ($sequenceType = $this->getSequenceType()) {
            $data['payment']['sequenceType'] = $sequenceType;
        }

        if ($cardToken = $this->getCardToken()) {
            $data['payment']['cardToken'] = $cardToken;
        }

        if ($this->hasEnablePaymentWebhook()) {
            $data['payment']['webhookUrl'] = $data['webhookUrl'];
        }

        return \array_filter($data);
    }

    /**
     * @return array<string, string|array>
     */
    protected function getCardData(CreditCard $card): array
    {
        $data = [];

        $data['billingAddress'] = \array_filter([
            'organizationName' => $card->getCompany(),
            'streetAndNumber' => $card->getAddress1(),
            'streetAdditional' => $card->getAddress2(),
            'city' => $card->getCity(),
            'region' => $card->getState(),
            'postalCode' => $card->getPostcode(),
            'country' => $card->getCountry(),
            'title' => $card->getTitle(),
            'givenName' => $card->getFirstName(),
            'familyName' => $card->getLastName(),
            'email' => $this->getBillingEmail() ?: $card->getEmail(),
            'phone' => $card->getPhone(),
        ]);

        if ($card->getShippingAddress1()) {
            $data['shippingAddress'] = \array_filter([
                'organizationName' => $card->getCompany(),
                'streetAndNumber' => $card->getShippingAddress1(),
                'streetAdditional' => $card->getShippingAddress2(),
                'city' => $card->getShippingCity(),
                'region' => $card->getShippingState(),
                'postalCode' => $card->getShippingPostcode(),
                'country' => $card->getShippingCountry(),
                'title' => $card->getShippingTitle(),
                'givenName' => $card->getShippingFirstName(),
                'familyName' => $card->getShippingLastName(),
                'email' => $card->getEmail(),
                'phone' => $card->getShippingPhone(),
            ]);
        }

        return $data;
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function getLines(ItemBag $items): array
    {
        $lines = [];
        foreach ($items as $item) {
            $vatRate = $item->getVatRate();
            $totalAmount = $item->getTotalAmount();
            $vatAmount = $item->getVatAmount();

            if (null === $totalAmount) {
                $totalAmount = $item->getQuantity() * $item->getPrice();
            }

            if (null === $vatAmount) {
                $vatAmount =  \round($totalAmount * $vatRate / (100 + $vatRate), $this->getCurrencyDecimalPlaces());
            }

            $data = [
                'type' => $item->getType(),
                'sku' => $item->getSku(),
                'name' => $item->getName(),
                'productUrl' => $item->getProductUrl(),
                'imageUrl' => $item->getImageUrl(),
                'quantity' => (int) $item->getQuantity(),
                'vatRate' => $vatRate,
                'unitPrice' => $this->createAmountObject($item->getUnitPrice()),
                'totalAmount' => $this->createAmountObject($totalAmount),
                'discountAmount' => $this->createAmountObject($item->getDiscountAmount()),
                'vatAmount' => $this->createAmountObject($vatAmount),
            ];

            // Strip null values
            $lines[] = \array_filter($data);
        }

        return $lines;
    }

    /** @param array<array-key, mixed> $data */
    public function sendData($data): CreateOrderResponse
    {
        $response = $this->sendRequest(
            self::POST,
            '/orders' . ($this->hasIncludePayments() ? '?embed=payments' : ''),
            $data,
        );

        if (\array_key_exists('id', $response)) {
            $this->setGatewayReference($response['id']);
            $this->setTransactionReference($response['id']);
        }

        return $this->response = new CreateOrderResponse($this, $response);
    }
}
