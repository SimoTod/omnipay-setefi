<?php

namespace SimoTod\OmnipaySetefi\Message;

use Omnipay\Common\Message\AbstractRequest;

class Request extends AbstractRequest
{
    protected $testEndpoint = 'https://test.monetaonline.it/monetaweb/payment/2/xml';
    protected $liveEndpoint = 'https://www.monetaonline.it/monetaweb/payment/2/xml';

    public function getData()
    {
        $this->validate(['id', 'password', 'operationType', 'amount', 'currencycode', 'language', 'merchantOrderId']);

        return $this->getParameters();
    }

    public function sendData($data)
    {
        $newData = array();
        $redirectUrl = null;

        try {
            $tokenRequest = $this->httpClient
                ->post($this->getEndpoint())
                ->setPostField('id', $this->getParameter('id'))
                ->setPostField('password', $this->getParameter('password'))
                ->setPostField('operationType', $this->getParameter('operationType'))
                ->setPostField('amount', $this->getParameter('amount'))
                ->setPostField('currencycode', $this->getParameter('currencycode'))
                ->setPostField('language', $this->getParameter('language'))
                ->setPostField('responseToMerchantUrl', $this->getParameter('responseToMerchantUrl'))
                ->setPostField('recoveryUrl', $this->getParameter('recoveryUrl'))
                ->setPostField('merchantOrderId', $this->getParameter('merchantOrderId'))
                ->setPostField('description', $this->getParameter('description'));
            $tokenResponse = $tokenRequest->send();

            $xml = simplexml_load_string($response->getBody()->__toString());

            $newData["reference"] = $xml->securitytoken;
            $newData["success"] = true;
            $redirectUrl = ($xml->hostedpageurl).'?paymentId='.($xml->paymentid);
            $data['message'] = "Success";
        } catch (Exception $e) {
            $newData["reference"] = null;
            $newData["success"] = false;
            $data['message'] = "Failure: ".$e->getMessage();
        }

        return $this->response = new Response($this, $newData, $redirectUrl);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
