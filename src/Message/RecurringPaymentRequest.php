<?php

namespace Omnipay\AfterPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class RecurringPaymentRequest extends AfterPayAuthorizeRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $data = [
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

        if ($items = $this->getItemData()) {
            $data['items'] = $items;
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return parent::getEndpoint() . '/recurring-payments';
    }

}
