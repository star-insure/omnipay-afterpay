<?php

namespace Omnipay\AfterPay\Message;

class ConfigurationRequest extends AfterPayAuthorizeRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'GET';
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return parent::getEndpoint() . '/configuration';
    }

    /**
     * @param Response $httpResponse
     * @return mixed
     */
    protected function parseResponseData(Response $httpResponse)
    {
        $data = parent::toJSON($httpResponse);

        if (!array_key_exists('errorCode', $data)) {
            return $data[0];
        }

        return $data;
    }
}
