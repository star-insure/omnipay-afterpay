<?php

namespace Omnipay\AfterPay\Message;

class PurchaseResponse extends Response
{
    /**
     * @return bool
     */
    public function isRedirect()
    {
        if ($this->isResponseHasError())
        {
            return false;
        }
        return true;
    }

    /**
     * @return string|void
     */
    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return (string) $this->data['redirectCheckoutUrl'];
        }
    }
}
