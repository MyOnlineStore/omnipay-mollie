<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Test\Message;

use Omnipay\Mollie\Gateway;
use Omnipay\Tests\TestCase;
use Psr\Http\Message\RequestInterface;

final class AbstractMollieRequestTest extends TestCase
{
    /** @var Gateway */
    protected $gateway;

    public function setUp(): void
    {
        $this->gateway = new Gateway($this->getHttpClient());
    }

    public function testVersionString(): void
    {
        $request = $this->gateway->fetchIssuers();
        $request->send();

        /** @var RequestInterface $httpRequest */
        $httpRequest = $this->getMockedRequests()[0];

        $versionString = 'Omnipay-Mollie/' . Gateway::GATEWAY_VERSION;
        $this->assertEquals($versionString, $httpRequest->getHeaderLine('User-Agent'));
    }

    public function testCustomVersionStrings(): void
    {
        $this->gateway->initialize([
            'versionStrings' => ['Acme/6.84']
        ]);
        $request = $this->gateway->fetchIssuers();
        $request->send();

        /** @var RequestInterface $httpRequest */
        $httpRequest = $this->getMockedRequests()[0];

        $versionString = 'Omnipay-Mollie/' . Gateway::GATEWAY_VERSION . ' Acme/6.84';
        $this->assertEquals($versionString, $httpRequest->getHeaderLine('User-Agent'));
    }

    public function testAddVersionString(): void
    {
        $this->gateway->addVersionString('Acme/6.84');
        $request = $this->gateway->fetchIssuers();
        $request->send();

        /** @var RequestInterface $httpRequest */
        $httpRequest = $this->getMockedRequests()[0];

        $versionString = 'Omnipay-Mollie/' . Gateway::GATEWAY_VERSION . ' Acme/6.84';
        $this->assertEquals($versionString, $httpRequest->getHeaderLine('User-Agent'));
    }
}
