<?php
/*
 * This file is part of the sparkpost-api-cli package.
 *
 * (c) Roman Sachse <r.sachse@ipark-media.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ipm\SparkpostApi\Sparkpost;

use SparkPost\SparkPost as Base;

/**
 * Dodgy extension to provide Subaccounts
 */
class Sparkpost extends Base
{
    /**
     * @var string
     */
    private $subaccount;

    /**
     * @param string $subaccount
     */
    public function addSubaccount($subaccount)
    {
        $this->subaccount = $subaccount;
    }

    /**
     * @return array
     */
    public function getHttpHeaders()
    {
        $headers = parent::getHttpHeaders();

        if($this->subaccount) {
            $headers = array_merge($headers, ['X-MSYS-SUBACCOUNT' => $this->subaccount]);
        }

        return $headers;
    }

}