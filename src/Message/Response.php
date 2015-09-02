<?php

namespace Omnipay\Dummy\Message;

use Omnipay\Common\Message\AbstractResponse;

class Response extends AbstractResponse
{
    protected $redirectUrl;

    public function __construct(RequestInterface $request, $data, $redirectUrl)
    {
        $this->request      = $request;
        $this->data         = $data;
        $this->redirectUrl  = $redirectUrl;
    }

    public function isSuccessful()
    {
        return isset($this->data['success']) && $this->data['success'];
    }

    public function isRedirect()
    {
        return $this->isSuccessful();
    }

    public function getTransactionReference()
    {
        return isset($this->data['reference']) ? $this->data['reference'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['message']) ? $this->data['message'] : null;
    }


    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}
