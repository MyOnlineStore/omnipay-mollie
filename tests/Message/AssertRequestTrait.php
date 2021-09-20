<?php

namespace Omnipay\Mollie\Test\Message;

use Psr\Http\Message\RequestInterface;

trait AssertRequestTrait
{
    public function assertEqualRequest(RequestInterface $expectedRequest, RequestInterface $actualRequest)
    {
        $this->assertEquals($expectedRequest->getMethod(), $actualRequest->getMethod(), 'Expected request Method should be equal to actual request method.');

        $this->assertEquals($expectedRequest->getUri(), $actualRequest->getUri(), 'Expected request Uri should be equal to actual request body.');

        if (empty((string) $expectedRequest->getBody())) {
            return;
        }

        $this->assertJsonStringEqualsJsonString((string) $expectedRequest->getBody(), (string) $actualRequest->getBody(), 'Expected request Body should be equal to actual request body.');
    }
}
