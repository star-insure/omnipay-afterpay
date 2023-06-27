<?php

namespace Omnipay\AfterPay\Message;

class RecurringPaymentRequest extends AfterPayAuthorizeRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        return [
            "requestId" => $this->getTransactionId(),
            "paymentMethod" => [
                "type"  => "BILLING_AGREEMENT",
                "token" => $this->getCardReference()
            ],
            'amount' => [
                'amount'   => $this->getAmount(),
                'currency' => $this->getCurrency()
            ],
            'merchantReference' => $this->getTransactionId()
        ];
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return parent::getEndpoint() . '/recurring-payments';
    }

}
