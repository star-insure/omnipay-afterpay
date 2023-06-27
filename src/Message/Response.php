<?php

namespace Omnipay\AfterPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{

    protected int $statusCode;

    /**
     * Constructor
     * @param RequestInterface $request
     * @param $data
     * @param $statusCode
     */
    public function __construct(RequestInterface $request, $data, $statusCode)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        if ($this->isRedirect()) {
            return false;
        }

        if ($this->isResponseHasError()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function isResponseHasError()
    {
        return array_key_exists('errorCode', $this->data) || !$this->isStatusCodeValid();
    }

    /**
     * @return bool
     */
    protected function isStatusCodeValid()
    {
        return $this->getStatusCode() < 400;
    }

    /**
     * Get Afterpay Message
     * @return mixed|string|null
     */
    public function getMessage()
    {
        if (isset($this->data['message'])) {
            return $this->data['message'];
        }

        if (!$this->isStatusCodeValid()) {
            return 'Afterpay returned an invalid status code. Please try again.';
        }

        return null;
    }

    /**
     * Get Status Code
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Get Token
     * @return mixed|null
     */
    public function getToken()
    {
        return isset($this->data['token']) ? $this->data['token'] : null;
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->getToken();
    }
}
