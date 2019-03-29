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
        $response = $responseFactory->build($transaction['payment_system']);

        if ($response->canceled()) {
            return $this->view->render('invoice/cancel');
        } else {
            // Now confirm payment by token, since its successful
            $this->getModuleService('transactionService')->confirmPayment($token);

            return $this->view->render('invoice/success');
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
            $gateway = ExtensionFactory::build($transaction['payment_system'], $transaction['id'], $transaction['amount'], $backUrl);

            return $this->view->disableLayout()->render('gateway', [
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
