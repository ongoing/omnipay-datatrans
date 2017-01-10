<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2016 Woche-Pass AG (http://www.w-vision.ch)
 * @license    GNU General Public License version 3 (GPLv3)
 */

namespace Omnipay\Datatrans\Message;

/**
 * Class XmlSettlementRequest
 *
 * @package Omnipay\Datatrans\Message
 */
class XmlAuthorizationRequest extends XmlRequest
{
    /**
     * @var array
     */
    protected $optionalParameters = [
        'reqtype',
        'uppCustomerIpAddress'
    ];

    /**
     * @var string
     */
    protected $apiEndpoint = 'XML_authorize.jsp';

    /**
     * @var string
     */
    protected $serviceName = 'authorizationService';

    /**
     * @var int
     */
    protected $serviceVersion = 3;

    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('merchantId', 'transactionId', 'sign', 'card');

        $data = array(
            'amount'     => $this->getAmountInteger(),
            'currency'   => $this->getCurrency(),
            'aliasCC'    => $this->getCard()->getNumber(),
            'expm'       => $this->getCard()->getExpiryMonth(),
            'expy'       => $this->getCard()->getExpiryDate('y'),
            'uppCustomerDetails' => [
                'uppCustomerIpAddress' => $_SERVER['REMOTE_ADDR']
            ],
            'useAlias' => 'no'
        );

        foreach ($this->optionalParameters as $param) {
            $value = $this->getParameter($param);

            if ($value) {
                $data[$param] = $value;
            }
        }

        return $data;
    }

    /**
     * @param $data
     * @return XmlAuthorizationResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new XmlAuthorizationResponse($this, $data);
    }
}