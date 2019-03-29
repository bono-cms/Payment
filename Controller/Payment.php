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

use Site\Controller\AbstractController;
use Payment\Extension\ExtensionFactory;
use Payment\Extension\ResponseFactory;

/**
 * Main payment controller, that handles all transactions
 */
final class Payment extends AbstractController
{
    /**
     * {@inheritDoc}
     */
    protected function bootstrap($action)
    {
        // Disabled CSRF for gateway action
        if ($action === 'successAction') {
            $this->enableCsrf = false;
        }

        parent::bootstrap($action);

        // Force to render templates only from current module
        $this->view->setModule('Payment')
                   ->setTheme('payment');
    }

    /**
     * Handle success or failure after payment gets done
     * 
     * @param string $token Unique transaction token
     * @return mixed
     */
    public function successAction($token)
    {
        // Find transaction row by its token
        $transaction = $this->getModuleService('transactionService')->fetchByToken($token);

        $responseFactory = new ResponseFactory($this->serviceLocator);
        $response = $responseFactory->build($transaction['extension']);

        if ($response->canceled()) {
            return $this->view->render('payment/cancel');
        } else {
            // Now confirm payment by token, since its successful
            $this->getModuleService('transactionService')->confirmPayment($token);

            return $this->view->render('payment/success');
        }
    }

    /**
     * Invokes gateway by its associated token
     * 
     * @param string $token Unique transaction token
     * @return mixed
     */
    public function gatewayAction($token)
    {
        // Find transaction row by its token
        $transaction = $this->getModuleService('transactionService')->fetchByToken($token);

        if ($transaction) {
            // Create back URL
            $backUrl = $this->request->getBaseUrl() . $this->createUrl('Payment:Payment@successAction', array($token));
            $gateway = ExtensionFactory::build($transaction['extension'], $transaction['id'], $transaction['amount'], $backUrl);

            return $this->view->disableLayout()->render('payment/gateway', [
                'gateway' => $gateway
            ]);

        } else {
            // Invalid token
            return false;
        }
    }

    /**
     * Creates new transaction
     * 
     * @return string
     */
    public function newAction()
    {
        
    }
}
