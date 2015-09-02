<?php

namespace SimoTod\OmnipaySetefi;

use Omnipay\Common\AbstractGateway;
use SimoTod\OmnipaySetefi\Message\Request;

class Gateway extends AbstractGateway
{
    const OP_TYPE_INIT = 'initialize';

    const CURRENCY_EURO = '978';
    const CURRENCY_US_DOLLAR = '998';
    const CURRENCY_UK_POUND = '826';

    const LANG_ITA = 'ITA';
    const LANG_DEU = 'DEU';
    const LANG_FRA = 'FRA';
    const LANG_SPA = 'SPA';
    const LANG_USA = 'USA';

    public function getName()
    {
        return 'Setefi';
    }

    public function getDefaultParameters()
    {
        return array(
            'id'                    => '99999999',
            'password'              => '99999999',
            'operationType'         => self::OP_TYPE_INIT,
            'amount'                => '1.00',
            'currencycode'          => self::CURRENCY_EURO,
            'language'              => self::LANG_ITA,
            'responseToMerchantUrl' => 'http://www.merchant.it/notify',
            'recoveryUrl'           => 'http://www.merchant.it/error',
            'merchantOrderId'       => 'TRCK0001',
            'description'           => 'Description'
        );
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\SimoTod\OmnipaySetefi\Message\Request', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        $merged_parameters = array_merge($this->getDefaultParameters(), $parameters);
        return $this->authorize($merged_parameters);
    }
}
