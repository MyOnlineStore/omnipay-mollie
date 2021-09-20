<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

use Omnipay\Common\Message\AbstractResponse;

class AbstractMollieResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return !isset($this->data['status'], $this->data['detail']);
    }

    public function getMessage(): string
    {
        return \json_encode($this->data);
    }
}
