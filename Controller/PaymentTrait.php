<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Payment\Controller;

use Payment\Extension\ResponseFactory;

/**
 * Shared trait to be used in controllers that process payments
 */
trait PaymentTrait
{
    /**
     * Renders payment gateway
     * 
     * @param string $controller
     * @param string $token
     * @param array $transaction
     * @return string
     */
    protected function renderGateway($controller, $token, array $transaction)
    {
        // Create back URL
        $backUrl = $this->request->getBaseUrl() . $this->createUrl($controller, [$token]);
        $gateway = ExtensionFactory::build($transaction['extension'], $transaction['amount'], $transaction['id'], $backUrl);

        return $this->view->disableLayout()->render('gateway', [
            'gateway' => $gateway
        ]);
    }

    /**
     * Creates response object
     * 
     * @param string $extension Payment extension
     * @return \Payment\Extension\ResponseHandlerInterface
     */
    protected function createResponse($extension)
    {
        $responseFactory = new ResponseFactory($this->serviceLocator);
        return $responseFactory->build($extension);
    }
}
