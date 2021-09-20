<?php
declare(strict_types=1);

namespace Omnipay\Mollie\Message\Response;

final class FetchProfileResponse extends AbstractMollieResponse
{
    public function getProfileReference(): string
    {
        return $this->data['id'];
    }

    public function isSuccessful(): bool
    {
        return isset($this->data['resource'], $this->data['id'])
            && 'profile' === $this->data['resource'];
    }
}
