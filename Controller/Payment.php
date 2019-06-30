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
use Payment\Collection\StatusCollection;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Validate\Pattern;

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
            return $this->view->render('cancel', array(
                'title' => 'Payment cancelation'
            ));
        } else {
            // Now confirm payment by token, since its successful
            $this->getModuleService('transactionService')->confirmPayment($token);

            return $this->view->render('success', array(
                'title' => 'Your payment has been accepted'
            ));
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
            // Don't allow processing finished transaction
            if ($transaction['status'] == StatusCollection::PARAM_STATUS_COMPLETE) {
                return $this->view->render('process-error');
            }

            // Create back URL
            $backUrl = $this->request->getBaseUrl() . $this->createUrl('Payment:Payment@successAction', array($token));
            $gateway = ExtensionFactory::build($transaction['extension'], $transaction['amount'], $transaction['id'], $backUrl);

            return $this->view->disableLayout()->render('gateway', array(
                'gateway' => $gateway
            ));

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
        if ($this->request->isGet()) {
            $entity = new VirtualEntity();

            // Fill amount and product if provided
            $entity['product'] = $this->request->getQuery('product');
            $entity['amount'] = $this->request->getQuery('amount', false);
            $entity['currency'] = 'USD';

            return $this->view->render('form', array(
                'entity' => $entity,
                'extensions' => $this->getModuleService('extensionService')->getExtensions(),
                'modules' => $this->getModuleService('extensionService')->getModules(),
                'title' => 'New payment'
            ));

        } else {
            $data = $this->request->getPost();

            // Build form validator
            $formValidator = $this->createValidator(array(
                'input' => array(
                    'source' => $data,
                    'definition' => array(
                        'payer' => new Pattern\Name(),
                        'email' => new Pattern\Email()
                    )
                )
            ));

            if ($formValidator->isValid()) {
                // Add now and get last token
                $token = $this->getModuleService('transactionService')->add(
                    $data['email'],
                    $data['payer'],
                    $data['amount'],
                    $data['currency'],
                    $data['module'],
                    $data['extension']
                );

                // If amount not provided, then update
                if (!isset($data['amount'])) {
                    $this->flashBag->set('success', 'Thanks! Your invoice has been sent');
                    return '1';
                } else {
                    // Otherwise redirect to payment page
                    return $this->json(array(
                        'backUrl' => $this->request->getBaseUrl() . $this->createUrl('Payment:Payment@gatewayAction', array($token))
                    ));
                }

            } else {
                return $formValidator->getErrors();
            }
        }
    }
}
