<?php

namespace Omnipay\AfterPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AfterPayAuthorizeRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $card = $this->getCard();

        // Normalize consumer names as AfterPay will reject the request with a missing surname
        $givenNames = $card->getFirstName();
        $surname = $card->getLastName();

        if (empty($surname) && false !== $pos = strrpos($givenNames, ' ')) {
            $surname = substr($givenNames, $pos + 1);
            $givenNames = substr($givenNames, 0, $pos);
        }

        $data = [
            'amount' => [
                'amount'   => $this->getAmount(),
                'currency' => $this->getCurrency()
            ],
            'consumer' => [
                'givenNames' => $givenNames,
                'surname'    => $surname,
                'email'      => $card->getEmail()
            ],
            'merchant' => [
                'redirectConfirmUrl' => $this->getReturnUrl(),
                'redirectCancelUrl'  => $this->getCancelUrl(),
            ],
            'merchantReference'      => $this->getTransactionId()
        ];

        // For payment with billing agreement
        if ($this->getBillingAgreement()) {
            $agreement = [
                'agreements' => [
                    [
                        'type' => 'BILLING',
                        'merchantReference' => $this->getTransactionId(),
                    ],
                ],
            ];
            $data = array_merge($data, $agreement);
        }

        // For payment with address
        if ($card->getBillingAddress1()) {
            $address = [
                'billing'     => array(
                    'name'        => $card->getBillingName(),       // Required
                    'line1'       => $card->getBillingAddress1(),   // Required
                    'line2'       => $card->getBillingAddress2(),
                    'area1'       => $card->getBillingCity(),       // Required
                    'region'      => $card->getBillingState(),      // Required
                    'postcode'    => $card->getBillingPostcode(),   // Required
                    'countryCode' => $card->getBillingCountry(),    // Required
                ),
                'shipping'    => array(
                    'name'        => $card->getBillingName(),
                    'line1'       => $card->getBillingAddress1(),
                    'line2'       => $card->getBillingAddress2(),
                    'area1'       => $card->getBillingCity(),
                    'region'      => $card->getBillingState(),
                    'postcode'    => $card->getBillingPostcode(),
                    'countryCode' => $card->getBillingCountry(),
                ),
            ];
            $data = array_merge($data, $address);
        }

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
        return parent::getEndpoint() . '/checkouts';
    }

    /**
     * @param $data
     * @param $statusCode
     * @return PurchaseResponse
     */
    protected function createResponse($data, $statusCode)
    {
        return new PurchaseResponse($this, $data, $statusCode);
    }

}
