<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Extension\Prime4G;

final class Prime4GFactory
{
    /**
     * Builds Prime4GFactory web form generator
     * 
     * @param mixed $price The price
     * @param int $orderId Order ID
     * @param string $back Back URL on success
     * @param array $credits
     * @return self
     */
    public function build($price, $orderId, $back, array $options = array())
    {
        $options = array_merge(include(__DIR__ . '/config.php'), $options);

        return new WebForm([
            'OrderID' => $orderId,
            'Password' => $options['Password'],
            'MerID' => $options['MerID'],
            'AcqID' => $options['AcqID'],
            'PurchaseAmt' => WebForm::normalizePrice($price),
            'MerRespURL' => $back,
            'action' => $options['action']
        ]);
    }
}
