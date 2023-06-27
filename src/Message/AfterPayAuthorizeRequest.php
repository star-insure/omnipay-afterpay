<?php

namespace Omnipay\AfterPay\Message;

use Omnipay\Common\Message\AbstractRequest;

abstract class AfterPayAuthorizeRequest extends AbstractRequest
{
    // Ref: https://developers.afterpay.com/afterpay-online/reference/api-environments
    protected string $liveEndpoint = 'https://global-api.afterpay.com/v2';
    protected string $testEndpoint = 'https://global-api-sandbox.afterpay.com/v2';

    /**
     * Get Merchant Id
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set Merchant Id
     * @param $value
     * @return AfterPayAuthorizeRequest
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get User Agent
     * @return string
     */
    public function getUserAgentPlatform()
    {
        return $this->getParameter('userAgentPlatform');
    }

    /**
     * @param $value
     * @return AfterPayAuthorizeRequest
     */
    public function setUserAgentPlatform($value)
    {
        return $this->setParameter('userAgentPlatform', $value);
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
     * @param $value
     * @return AfterPayAuthorizeRequest
     */
    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    /**
     * Get User Agent Merchant Url
     * @return string
     */
    public function getUserAgentMerchantUrl()
    {
        return $this->getParameter('userAgentMerchantUrl');
    }

    /**
     * Set User Agent Merchant Url
     * @param $value
     * @return AfterPayAuthorizeRequest
     */
    public function setUserAgentMerchantUrl($value)
    {
        return $this->setParameter('userAgentMerchantUrl', $value);
    }

    /**
     * Get Merchant Secret
     * @return string
     */
    public function getMerchantSecret()
    {
        return $this->getParameter('merchantSecret');
    }

    /**
     * Set Merchant Secret
     * @param $value
     * @return AfterPayAuthorizeRequest
     */
    public function setMerchantSecret($value)
    {
        return $this->setParameter('merchantSecret', $value);
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
     * Set Billing Agreement
     * @param bool $value
     * @return AfterPayAuthorizeRequest
     */
    public function setBillingAgreement(bool $value)
    {
        return $this->setParameter('billingAgreement', $value);
    }

    /**
     * Get Headers
     * @return array
     */
    public function getHeaders()
    {
        $headers = [];
        return $headers;
    }

    /**
     * Get Http Method
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * Get Endpoint
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * Send Data
     * @param $data
     * @return Response
     */
    public function sendData($data)
    {
        $headers = [
            'User-Agent'    => $this->getUserAgent(),
            'Authorization' => $this->buildAuthorizationHeader(),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];

        if ($this->getHttpMethod() == 'GET') {
            $httpResponse = $this->httpClient->request('GET', $this->getEndpoint() . '?' . http_build_query($data), $headers);
        } else {
            $httpResponse = $this->httpClient->request('POST',  $this->getEndpoint(), $headers, json_encode($data));
        }

        return $this->createResponse(json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());
    }

    /**
     * Convert to JSON Data
     * @param $data
     * @param $options
     * @return array|false|string|string[]
     */
    public function toJSON($data, $options = 0)
    {
        if (version_compare(phpversion(), '5.4.0', '>=') === true) {
            return json_encode($data, $options | 64);
        }
        return str_replace('\\/', '/', json_encode($data, $options));
    }

    /**
     * Create Response
     * @param $data
     * @param $status_code
     * @return Response
     */
    protected function createResponse($data, $status_code)
    {
        return $this->response = new Response($this, $data, $status_code);
    }

    /**
     * Build Authorization Header
     * @return string
     */
    protected function buildAuthorizationHeader()
    {
        $merchantId = $this->getMerchantId();
        $merchantSecret = $this->getMerchantSecret();

        return 'Basic ' . base64_encode($merchantId . ':' . $merchantSecret);
    }

    /**
     * Get User Agent
     * Sample: {pluginOrModuleOrClientLibrary}/{pluginVersion} ({platform}/{platformVersion}; Merchant/{merchantId})environmentURL
     * @return string
     */
    protected function getUserAgent() {

        $subinfo = [];

        // Platform
        if ($platform = $this->getUserAgentPlatform()) {
            $subinfo[] = $platform;
        } else {
            $subinfo[] = 'star-insure-omnipay-afterpay/1.0';
        }

        // PHP Version
        $subinfo[] = 'PHP/'.PHP_VERSION;
        $subinfo[] = 'Merchant/'.$this->getMerchantId();

        return 'Star-Insure-Omnipay-Afterpay ('.join('; ', $subinfo).')' . $this->getUserAgentMerchantUrl();
    }
}
