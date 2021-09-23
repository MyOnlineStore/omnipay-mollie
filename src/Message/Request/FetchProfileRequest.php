<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\FetchProfileResponse;

final class FetchProfileRequest extends AbstractMollieRequest
{
    /**
     * @return array<string, mixed>
     *
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('apiKey');

        return [];
    }

    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(self::GET, '/profiles/me');

        return new FetchProfileResponse($this, $response);
    }
}
