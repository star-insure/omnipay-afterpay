<?php

namespace Omnipay\AfterPay;

use Omnipay\Common\AbstractGateway;

/**
 * After Pay Gateway
 * Derived from:
 * https://github.com/mediabeastnz/omnipay-afterpay
 * https://github.com/thephpleague/omnipay-paymentexpress
 */
class Gateway extends AbstractGateway
{
    public string $countryCode = 'NZ';
    public function getName()
    {
        return 'AfterPay';
    }

    /**
     * Get Default Parameters
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'merchantId'           => '',
            'merchantSecret'       => '',
            'userAgentMerchantUrl' => '',
            'userAgentPlatform'    => '',
            'countryCode'          => '',
            'billingAgreement'     => false,
            'testMode'             => false,
        );
    }

    /**
     * Get Merchant ID
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set Merchant ID
     * @param mixed $value
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get Merchant Secret
     * @return mixed
     */
    public function getMerchantSecret()
    {
        return $this->getParameter('merchantSecret');
    }

    /**
     * Set Merchant Secret
     * @param mixed $value
     * @return $this
     */
    public function setMerchantSecret($value)
    {
        return $this->setParameter('merchantSecret', $value);
    }

    /**
     * Get User Agent
     * @return mixed
     */
    public function getUserAgentPlatform()
    {
        return $this->getParameter('userAgentPlatform');
    }

    /**
     * Set User Agent
     * @param mixed $value
     * @return $this
     */
    public function setUserAgentPlatform($value)
    {
        return $this->setParameter('userAgentPlatform', $value);
    }

    /**
     * Get User Agent Merchant Url
     * @return mixed
     */
    public function getUserAgentMerchantUrl()
    {
        return $this->getParameter('userAgentMerchantUrl');
    }

    /**
     * Set Merchant Url
     * @param mixed $value
     * @return $this
     */
    public function setUserAgentMerchantUrl($value)
    {
        return $this->setParameter('userAgentMerchantUrl', $value);
    }


    /**
     * Get Country Code
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->getParameter('countryCode');
    }

    /**
     * Set Country Code
     * @param mixed $value
     * @return $this
     */
    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    /**
     * Get Billing Agreement
     * @return bool
     */
    public function getBillingAgreement() : bool
    {
        return $this->getParameter('billingAgreement');
    }

    /**
     * @param bool $value
     * @return Gateway
     */
    public function setBillingAgreement(bool $value)
    {
        return $this->setParameter('billingAgreement', $value);
    }

    /**
     * Configuration Request.
     * Retrieves a list of payment configuration that includes payment types
     * and valid payment ranges. A request to create an Order will be rejected
     * if the total amount is not between (inclusive) the minimumAmount and
     * minimumAmount.
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function configuration(array $options = array())
    {
        return $this->createRequest('\Omnipay\AfterPay\Message\ConfigurationRequest', $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\RequestInterface
     */
    public function purchase(array $options = array())
    {
        if (!empty($options['cardReference'])) {
            return $this->createRequest('\Omnipay\AfterPay\Message\RecurringPaymentRequest', $options);
        }
        return $this->createRequest('\Omnipay\AfterPay\Message\PurchaseRequest', $options);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\RequestInterface
     */
    public function completePurchase(array $options = array())
    {
        return $this->createRequest('\Omnipay\AfterPay\Message\CompletePurchaseRequest', $options);
    }

}
