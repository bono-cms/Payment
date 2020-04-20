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
use Payment\Extension\ExtensionFactory;

/**
 * Shared trait to be used in controllers that process payments
 */
trait PaymentTrait
{
    /**
     * Configures view instance to render templates from payment module
     * 
     * @return void
     */
    protected function switchToPaymentView()
    {
        // Force to render templates only from current module
        $this->view->setModule('Payment')
                   ->setTheme('payment');
    }

    /**
     * Render response template
     * 
     * @param string $code Response code constant
     * @return string
     */
    protected function renderResponse($code)
    {
        $this->switchToPaymentView();

        return $this->view->render('response', [
            'code' => $code
        ]);
    }

    /**
     * Renders payment gateway
     * 
     * @param string $controller Route to response handler
     * @param array|\ArrayAccess $transaction Transaction row
     * @return string
     */
    protected function renderGateway($controller, $transaction)
    {
        $this->switchToPaymentView();

        // Create back URL
        $backUrl = $this->request->getBaseUrl() . $this->createUrl($controller, [$transaction['token']]);
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
