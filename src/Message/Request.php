<?php

namespace SimoTod\OmnipaySetefi\Message;

use Omnipay\Common\Message\AbstractRequest;

class Request extends AbstractRequest
{
    protected $testEndpoint = 'https://test.monetaonline.it/monetaweb/payment/2/xml';
    protected $liveEndpoint = 'https://www.monetaonline.it/monetaweb/payment/2/xml';

    const OP_TYPE_INIT = 'initialize';

    public function getData()
    {
        $this->validate('id', 'password', 'amount', 'transactionId');

        return $this->getParameters();
    }

    public function setId($id)
    {
        return $this->setParameter('id', $id);
    }

    public function setPassword($password)
    {
        return $this->setParameter('password', $password);
    }

    public function setLanguage($language)
    {
        return $this->setParameter('language', $language);
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
                ->setPostField('operationType', self::OP_TYPE_INIT)
                ->setPostField('amount', $this->getParameter('amount'))
                ->setPostField('currencycode', $this->getParameter('currency'))
                ->setPostField('language', $this->getParameter('language'))
                ->setPostField('responseToMerchantUrl', $this->getParameter('returnUrl'))
                ->setPostField('recoveryUrl', $this->getParameter('cancelUrl'))
                ->setPostField('merchantOrderId', $this->getParameter('transactionId'))
                ->setPostField('description', $this->getParameter('description'));
            $tokenResponse = $tokenRequest->send();

            $xml = simplexml_load_string($tokenResponse->getBody()->__toString());

            if ($xml->errorcode) {
                $newData["reference"] = null;
                $data['message'] = "Failure: ".$xml->errormessage->__toString();
            } else {
                $newData["reference"] = [
                    'securitytoken' => $xml->securitytoken->__toString(),
                    'paymentid'     => $xml->paymentid->__toString(),
                ];
                $data['message'] = "Success";
                $redirectUrl = ($xml->hostedpageurl->__toString()).'?paymentId='.($xml->paymentid->__toString());
            }
        } catch (Exception $e) {
            $newData["reference"] = null;
            $data['message'] = "Failure: ".$e->getMessage();
        }

        return $this->response = new Response($this, $newData, $redirectUrl);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
