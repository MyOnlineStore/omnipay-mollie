<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Mollie\Message\Request\CreateOrderRequest;
use Omnipay\Mollie\Message\Response\CreateOrderResponse;
use Omnipay\Mollie\Message\Response\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

final class CreateOrderRequestTest extends TestCase
{
    use AssertRequestTrait;

    protected CreateOrderRequest $request;

    public function setUp(): void
    {
        $this->request = new CreateOrderRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            [
                'apiKey' => 'test_dHar4XY7LxsDOtmnkVtjNVWXLSlXsM',
                'amount' => '1027.99',
                'currency' => 'EUR',
                'orderNumber' => '1337',
                'lines' => [
                    [
                        'type' => 'physical',
                        'sku' => '5702016116977',
                        'name' => 'LEGO 42083 Bugatti Chiron',
                        'productUrl' => 'https://shop.lego.com/nl-NL/Bugatti-Chiron-42083',
                        'imageUrl' => 'https://sh-s7-live-s.legocdn.com/is/image//LEGO/42083_alt1?$main$',
                        'quantity' => 2,
                        'vatRate' => '21.00',
                        'unitPrice' => '399.00',
                        'totalAmount' => '698.00',
                        'discountAmount' => '100.00',
                        'vatAmount' => '121.14',
                    ],
                    [
                        'type' => 'physical',
                        'sku' => '5702015594028',
                        'name' => 'LEGO 42056 Porsche 911 GT3 RS',
                        'productUrl' => 'https://shop.lego.com/nl-NL/Porsche-911-GT3-RS-42056',
                        'imageUrl' => 'https://sh-s7-live-s.legocdn.com/is/image/LEGO/42056?$PDPDefault$',
                        'quantity' => 1,
                        'vatRate' => '21.00',
                        'unitPrice' => '329.99',
                        'totalAmount' => '329.99',
                        'vatAmount' => '57.27',
                    ],
                ],
                'card' => [
                    'company' => 'Mollie B.V.',
                    'email' => 'norris@chucknorrisfacts.net',
                    'birthday' => '1958-01-31',
                    'billingTitle' => 'Dhr',
                    'billingFirstName' => 'Piet',
                    'billingLastName' => 'Mondriaan',
                    'billingAddress1' => 'Keizersgracht 313',
                    'billingCity' => 'Amsterdam',
                    'billingPostcode' => '1234AB',
                    'billingState' => 'Noord-Holland',
                    'billingCountry' => 'NL',
                    'billingPhone' => '+31208202070',
                    'shippingTitle' => 'Mr',
                    'shippingFirstName' => 'Chuck',
                    'shippingLastName' => 'Norris',
                    'shippingAddress1' => 'Prinsengracht 313',
                    'shippingAddress2' => '4th floor',
                    'shippingCity' => 'Haarlem',
                    'shippingPostcode' => '5678AB',
                    'shippingState' => 'Noord-Holland',
                    'shippingCountry' => 'NL',
                ],
                'metadata' => [
                    'order_id' => '1337',
                    'description' => 'Lego cars',
                ],
                'locale' => 'nl_NL',
                'returnUrl' => 'https://example.org/redirect',
                'notifyUrl' => 'https://example.org/webhook',
                'paymentMethod' => 'klarnapaylater',
                'billingEmail' => 'piet@mondriaan.com',
                'cardToken' => 'qwe123asd',
            ]
        );
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        $this->assertSame(['value' => '1027.99', 'currency' => 'EUR'], $data['amount']);
        $this->assertSame('1337', $data['orderNumber']);
        $this->assertSame('https://example.org/redirect', $data['redirectUrl']);
        $this->assertSame('klarnapaylater', $data['method']);
        $this->assertSame('Lego cars', $data['metadata']['description']);
        $this->assertSame('nl_NL', $data['locale']);

        $this->assertCount(12, $data);
    }

    public function testGetAddressData(): void
    {
        $data = $this->request->getData();

        $shippingAddress = $data['shippingAddress'];
        self::assertIsArray($shippingAddress);
        $this->assertSame('Mollie B.V.', $shippingAddress['organizationName']);
        $this->assertSame('Prinsengracht 313', $shippingAddress['streetAndNumber']);
        $this->assertSame('4th floor', $shippingAddress['streetAdditional']);
        $this->assertSame('Haarlem', $shippingAddress['city']);
        $this->assertSame('Noord-Holland', $shippingAddress['region']);
        $this->assertSame('5678AB', $shippingAddress['postalCode']);
        $this->assertSame('NL', $shippingAddress['country']);
        $this->assertSame('Mr', $shippingAddress['title']);
        $this->assertSame('Chuck', $shippingAddress['givenName']);
        $this->assertSame('Norris', $shippingAddress['familyName']);
        $this->assertSame('norris@chucknorrisfacts.net', $shippingAddress['email']);

        $billingAddress = $data['billingAddress'];
        self::assertIsArray($billingAddress);
        $this->assertSame('Mollie B.V.', $billingAddress['organizationName']);
        $this->assertSame('Keizersgracht 313', $billingAddress['streetAndNumber']);
        $this->assertSame('Amsterdam', $billingAddress['city']);
        $this->assertSame('Noord-Holland', $billingAddress['region']);
        $this->assertSame('1234AB', $billingAddress['postalCode']);
        $this->assertSame('NL', $billingAddress['country']);
        $this->assertSame('Dhr', $billingAddress['title']);
        $this->assertSame('Piet', $billingAddress['givenName']);
        $this->assertSame('Mondriaan', $billingAddress['familyName']);
        $this->assertSame('piet@mondriaan.com', $billingAddress['email']);
        $this->assertSame('+31208202070', $billingAddress['phone']);
    }

    public function testGetLines(): void
    {
        $data = $this->request->getData();

        self::assertIsArray($data['lines']);
        $this->assertCount(2, $data['lines']);

        $line = $data['lines'][0];
        self::assertIsArray($line);
        $this->assertSame('physical', $line['type']);
        $this->assertSame('5702016116977', $line['sku']);
        $this->assertSame('LEGO 42083 Bugatti Chiron', $line['name']);
        $this->assertSame('https://shop.lego.com/nl-NL/Bugatti-Chiron-42083', $line['productUrl']);
        $this->assertSame('https://sh-s7-live-s.legocdn.com/is/image//LEGO/42083_alt1?$main$', $line['imageUrl']);
        $this->assertSame(2, $line['quantity']);
        $this->assertSame('21.00', $line['vatRate']);
        $this->assertSame('399.00', $line['unitPrice']['value']);
        $this->assertSame('698.00', $line['totalAmount']['value']);
        $this->assertSame('100.00', $line['discountAmount']['value']);
        $this->assertSame('121.14', $line['vatAmount']['value']);
    }

    public function testDiscountLines(): void
    {
        $this->request->setLines(
            [
                [
                    'type' => 'physical',
                    'sku' => '5702016116977',
                    'name' => 'LEGO 42083 Bugatti Chiron',
                    'quantity' => 2,
                    'vatRate' => '21.00',
                    'unitPrice' => '399.00',
                    'totalAmount' => '698.00',
                    'discountAmount' => '100.00',
                    'vatAmount' => '121.14',
                ],
                [
                    'type' => 'discount',
                    'name' => 'Discount 100 EURO',
                    'quantity' => 1,
                    'vatRate' => '21.00',
                    'unitPrice' => '-100.00',
                    'totalAmount' => '-100.00',
                    'vatAmount' => '-17.36',
                ],
            ]
        );

        $this->setMockHttpResponse('CreateOrderSuccess.txt');
        $response = $this->request->send();

        $this->assertInstanceOf(CreateOrderResponse::class, $response);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CreateOrderSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request(
                'POST',
                'https://api.mollie.com/v2/orders',
                [],
                \file_get_contents(__DIR__ . '/../Mock/CreateOrderRequest.txt')
            ),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(CreateOrderResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertSame('https://www.mollie.com/payscreen/order/checkout/pbjz8x', $response->getRedirectUrl());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('ord_pbjz8x', $response->getTransactionReference());
        $this->assertSame('created', $response->getStatus());
        $this->assertTrue($response->isOpen());
        $this->assertFalse($response->isPaid());
        $this->assertNull($response->getCode());
    }

    public function testSendFailure(): void
    {
        $this->setMockHttpResponse('CreateOrderFailure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request('POST', 'https://api.mollie.com/v2/orders'),
            $this->getMockClient()->getLastRequest()
        );

        $this->assertInstanceOf(CreateOrderResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame(
            '{"status":401,"title":"Unauthorized Request","detail":"Missing authentication, or failed to authenticate","_links":{"documentation":{"href":"https:\/\/docs.mollie.com\/guides\/authentication","type":"text\/html"}}}',
            $response->getMessage()
        );
    }

    public function testSendWithPaymentEmbeddedSuccess(): void
    {
        self::assertFalse($this->request->hasIncludePayments());
        self::assertFalse($this->request->hasEnablePaymentWebhook());

        $data = $this->request->getData();
        self::assertArrayNotHasKey('webhookUrl', $data['payment']);

        $this->request->setIncludePayments();
        $this->request->setEnablePaymentWebhook();

        $data = $this->request->getData();

        self::assertTrue($this->request->hasIncludePayments());
        self::assertTrue($this->request->hasEnablePaymentWebhook());
        self::assertArrayHasKey('webhookUrl', $data['payment']);
        self::assertSame($data['payment']['webhookUrl'], $data['webhookUrl']);

        $this->setMockHttpResponse('CreateOrderWithPaymentSuccess.txt');
        $response = $this->request->send();

        self::assertStringContainsString(
            '?embed=payments',
            (string) $this->getMockClient()->getLastRequest()->getUri()
        );
        self::assertInstanceOf(CreateOrderResponse::class, $response);
        self::assertFalse($response->isSuccessful());

        $payments = $response->getPayments();

        self::assertCount(1, $payments);
        self::assertContainsOnlyInstancesOf(FetchTransactionResponse::class, $payments);
    }
}
