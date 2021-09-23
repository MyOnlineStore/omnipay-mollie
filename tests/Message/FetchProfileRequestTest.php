<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Mollie\Message\Request\FetchProfileRequest;
use Omnipay\Mollie\Message\Response\FetchProfileResponse;
use Omnipay\Tests\TestCase;

final class FetchProfileRequestTest extends TestCase
{
    use AssertRequestTrait;

    /** @var FetchProfileRequest */
    private $request;

    protected function setUp(): void
    {
        $this->request = new FetchProfileRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(['apiKey' => 'myKey']);
    }

    public function testGetData(): void
    {
        $data = $this->request->getData();

        self::assertCount(0, $data);
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('FetchProfileSuccess.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request('GET', 'https://api.mollie.com/v2/profiles/me'),
            $this->getMockClient()->getLastRequest()
        );

        self::assertInstanceOf(FetchProfileResponse::class, $response);

        self::assertTrue($response->isSuccessful());
        self::assertSame('pfl_dfgh', $response->getProfileReference());
    }

    public function testSendFailure(): void
    {
        $this->setMockHttpResponse('FetchProfileFailure.txt');
        $response = $this->request->send();

        $this->assertEqualRequest(
            new Request('GET', 'https://api.mollie.com/v2/profiles/me'),
            $this->getMockClient()->getLastRequest()
        );

        self::assertInstanceOf(FetchProfileResponse::class, $response);

        self::assertFalse($response->isSuccessful());
    }
}
