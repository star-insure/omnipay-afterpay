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
        $card = $this->getCard();

        // Normalize consumer names as AfterPay will reject the request with a missing surname
        $givenNames = $card->getFirstName();
        $surname = $card->getLastName();

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
            'consumer' => [
                'givenNames' => $givenNames,
                'surname'    => $surname,
                'email'      => $card->getEmail()
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
